<?php

namespace App\Http\Requests\Portal;

use App\Enums\NewsletterStatus;
use App\Models\NewsletterSubscriber;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNewsletterSubscriberStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $subscriber = $this->route('newsletterSubscriber');

        return $subscriber instanceof NewsletterSubscriber
            && ($this->user()?->can('update', $subscriber) ?? false);
    }

    /**
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::enum(NewsletterStatus::class)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Choose a subscription status.',
        ];
    }

    public function status(): NewsletterStatus
    {
        return NewsletterStatus::from($this->validated('status'));
    }
}
