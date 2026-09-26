<?php

namespace App\Http\Requests\Portal;

use App\Enums\TestimonyStatus;
use App\Models\Testimony;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTestimonyStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to review this testimony.
     */
    public function authorize(): bool
    {
        $testimony = $this->route('testimony');

        return $testimony instanceof Testimony
            && ($this->user()?->can('update', $testimony) ?? false);
    }

    /**
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::enum(TestimonyStatus::class)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Choose whether to approve or decline this testimony.',
        ];
    }

    public function status(): TestimonyStatus
    {
        return TestimonyStatus::from($this->string('status')->toString());
    }
}
