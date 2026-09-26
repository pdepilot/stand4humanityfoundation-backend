<?php

namespace App\Http\Requests;

use App\Enums\DonationCause;
use App\Enums\DonationCurrency;
use App\Enums\DonationFrequency;
use App\Enums\DonationPaymentMethod;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDonationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalize camelCase fields from the public donate form.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('paymentMethod') && ! $this->filled('payment_method')) {
            $this->merge([
                'payment_method' => $this->input('paymentMethod'),
            ]);
        }

        if ($this->has('anonymous')) {
            $this->merge([
                'anonymous' => filter_var($this->input('anonymous'), FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }

    /**
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'string', 'email', 'max:120'],
            'phone' => ['required', 'string', 'max:40'],
            'location' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:200'],
            'amount' => ['required', 'numeric', 'min:1'],
            'currency' => ['required', 'string', Rule::enum(DonationCurrency::class)],
            'cause' => ['required', 'string', Rule::enum(DonationCause::class)],
            'frequency' => ['required', 'string', Rule::enum(DonationFrequency::class)],
            'payment_method' => ['required', 'string', Rule::enum(DonationPaymentMethod::class)],
            'anonymous' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Enter the donor name.',
            'email.required' => 'Enter the donor email.',
            'email.email' => 'Enter a valid email address.',
            'phone.required' => 'Enter the donor phone number.',
            'amount.required' => 'Enter a donation amount.',
            'amount.min' => 'Enter a donation amount of at least 1.',
            'currency.required' => 'Choose a currency.',
            'cause.required' => 'Choose a donation cause.',
            'frequency.required' => 'Choose how often this gift is given.',
            'payment_method.required' => 'Choose a payment method.',
        ];
    }

    /**
     * @return array{
     *     name: string,
     *     email: string,
     *     phone: string,
     *     location: string|null,
     *     description: string|null,
     *     amount: float,
     *     currency: string,
     *     cause: string,
     *     frequency: string,
     *     payment_method: string,
     *     anonymous: bool
     * }
     */
    public function donationDetails(): array
    {
        $validated = $this->safe()->only([
            'name',
            'email',
            'phone',
            'location',
            'description',
            'amount',
            'currency',
            'cause',
            'frequency',
            'payment_method',
            'anonymous',
        ]);

        return [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'location' => $validated['location'] ?? null,
            'description' => $validated['description'] ?? null,
            'amount' => $validated['amount'],
            'currency' => $validated['currency'],
            'cause' => $validated['cause'],
            'frequency' => $validated['frequency'],
            'payment_method' => $validated['payment_method'],
            'anonymous' => (bool) ($validated['anonymous'] ?? false),
        ];
    }
}
