<?php

namespace App\Http\Requests\Portal;

use App\Enums\AuditReport;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExportAuditReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdministrator() ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        return [
            'report' => ['required', Rule::enum(AuditReport::class)],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'report.required' => 'Choose a report to export.',
            'to.after_or_equal' => 'The end date must be on or after the start date.',
        ];
    }

    public function report(): AuditReport
    {
        return AuditReport::from($this->string('report')->toString());
    }

    public function fromDate(): ?string
    {
        $from = $this->input('from');

        return is_string($from) && $from !== '' ? $from : null;
    }

    public function toDate(): ?string
    {
        $to = $this->input('to');

        return is_string($to) && $to !== '' ? $to : null;
    }
}
