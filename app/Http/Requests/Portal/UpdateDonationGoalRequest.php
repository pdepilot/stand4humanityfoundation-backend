<?php

namespace App\Http\Requests\Portal;

use App\Enums\DonationCause;
use App\Models\DonationSetting;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDonationGoalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update', DonationSetting::current()) ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'goals' => ['required', 'array'],
            'spent_amount' => ['required', 'numeric', 'min:0', 'max:999999999999.99'],
        ];

        foreach (DonationCause::publicGoalCauses() as $cause) {
            $rules['goals.'.$cause->value] = ['required', 'numeric', 'min:0', 'max:999999999999.99'];
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        $messages = [
            'goals.required' => 'Enter a goal for each programme.',
            'spent_amount.required' => 'Enter the amount spent so far.',
            'spent_amount.numeric' => 'Enter the amount spent as a number.',
            'spent_amount.min' => 'Enter an amount spent of at least 0.',
        ];

        foreach (DonationCause::publicGoalCauses() as $cause) {
            $key = 'goals.'.$cause->value;
            $messages[$key.'.required'] = 'Enter the '.$cause->label().' goal.';
            $messages[$key.'.numeric'] = 'Enter the '.$cause->label().' goal as a number.';
            $messages[$key.'.min'] = 'Enter a goal of at least 0 for '.$cause->label().'.';
        }

        return $messages;
    }

    /**
     * @return array<string, float>
     */
    public function goalAmounts(): array
    {
        $goals = $this->safe()->only(['goals'])['goals'] ?? [];
        $amounts = [];

        foreach (DonationCause::publicGoalCauses() as $cause) {
            $amounts[$cause->goalColumn()] = (float) ($goals[$cause->value] ?? 0);
        }

        $amounts['spent_amount'] = $this->spentAmount();

        return $amounts;
    }

    public function spentAmount(): float
    {
        return (float) $this->validated('spent_amount');
    }
}
