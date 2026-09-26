<?php

namespace App\Http\Requests\Portal;

use App\Models\MeetingAttendance;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MarkMeetingAttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', MeetingAttendance::class) ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        return [
            'foundation_member_id' => ['required', 'integer', 'exists:foundation_members,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'foundation_member_id.required' => 'Choose a member to mark present.',
            'foundation_member_id.exists' => 'That member is not on the roll.',
        ];
    }

    public function memberId(): int
    {
        return $this->integer('foundation_member_id');
    }
}
