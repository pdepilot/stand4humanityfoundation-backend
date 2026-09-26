<?php

namespace App\Http\Requests\Portal;

use App\Enums\IncidentStatus;
use App\Models\IncidentReport;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateIncidentReportStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $report = $this->route('incidentReport');

        return $report instanceof IncidentReport
            && ($this->user()?->can('update', $report) ?? false);
    }

    /**
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::enum(IncidentStatus::class)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Choose a case status.',
        ];
    }

    public function status(): IncidentStatus
    {
        return IncidentStatus::from($this->validated('status'));
    }
}
