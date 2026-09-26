<?php

namespace App\Http\Requests;

use App\Enums\IncidentRelationship;
use App\Enums\IncidentType;
use App\Enums\VictimGender;
use App\Support\NigerianState;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class StoreIncidentReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $nullable = [
            'full_name',
            'phone',
            'email',
            'alt_contact',
            'incident_time',
            'lga',
            'community',
            'full_address',
            'victim_name',
            'victim_age',
            'victim_gender',
            'victim_phone',
            'victim_last_location',
            'suspect_name',
            'suspect_alias',
            'suspect_description',
            'suspect_address',
            'suspect_vehicle',
            'suspect_notes',
            'incident_address',
            'latitude',
            'longitude',
        ];

        $emptyToNull = [];

        foreach ($nullable as $field) {
            if ($this->input($field) === '') {
                $emptyToNull[$field] = null;
            }
        }

        $time = $this->input('incident_time');

        if (is_string($time) && preg_match('/^\d{2}:\d{2}:\d{2}$/', $time) === 1) {
            $emptyToNull['incident_time'] = substr($time, 0, 5);
        }

        $this->merge($emptyToNull);
    }

    /**
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        $anonymous = IncidentRelationship::AnonymousReporter->value;

        return [
            'website' => ['nullable', 'string', 'max:0'],
            'full_name' => ['required_unless:relationship,'.$anonymous, 'nullable', 'string', 'max:120'],
            'phone' => ['required_unless:relationship,'.$anonymous, 'nullable', 'string', 'max:40'],
            'email' => ['nullable', 'string', 'email', 'max:120'],
            'alt_contact' => ['nullable', 'string', 'max:120'],
            'relationship' => ['required', 'string', Rule::enum(IncidentRelationship::class)],
            'incident_type' => ['required', 'string', Rule::enum(IncidentType::class)],
            'incident_title' => ['required', 'string', 'max:160'],
            'incident_date' => ['required', 'date', 'before_or_equal:today'],
            'incident_time' => ['nullable', 'date_format:H:i'],
            'state' => ['required', 'string', Rule::in(NigerianState::names())],
            'lga' => ['nullable', 'string', 'max:80'],
            'community' => ['nullable', 'string', 'max:120'],
            'full_address' => ['nullable', 'string', 'max:200'],
            'description' => ['required', 'string', 'min:50', 'max:5000'],
            'victim_name' => ['nullable', 'string', 'max:120'],
            'victim_age' => ['nullable', 'integer', 'min:0', 'max:120'],
            'victim_gender' => ['nullable', 'string', Rule::enum(VictimGender::class)],
            'victim_phone' => ['nullable', 'string', 'max:40'],
            'victim_last_location' => ['nullable', 'string', 'max:160'],
            'suspect_name' => ['nullable', 'string', 'max:120'],
            'suspect_alias' => ['nullable', 'string', 'max:80'],
            'suspect_description' => ['nullable', 'string', 'max:200'],
            'suspect_address' => ['nullable', 'string', 'max:200'],
            'suspect_vehicle' => ['nullable', 'string', 'max:160'],
            'suspect_notes' => ['nullable', 'string', 'max:2000'],
            'incident_address' => ['nullable', 'string', 'max:200'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'confirm_accurate' => ['accepted'],
            'confirm_false' => ['accepted'],
            'consent_processing' => ['accepted'],
            'evidence' => ['nullable', 'array', 'max:5'],
            'evidence.*' => [
                'file',
                'extensions:jpg,jpeg,png,gif,webp,pdf,doc,docx,mp4,mov,webm,avi',
                'mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,mp4,mov,webm,avi',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'website.max' => 'Unable to accept this report.',
            'full_name.required_unless' => 'Enter the reporter name, or choose Anonymous Reporter.',
            'phone.required_unless' => 'Enter a phone number, or choose Anonymous Reporter.',
            'relationship.required' => 'Select the relationship to the incident.',
            'incident_type.required' => 'Select the type of incident.',
            'incident_title.required' => 'Enter an incident title.',
            'incident_date.required' => 'Enter the date of the incident.',
            'state.required' => 'Select the state.',
            'description.required' => 'Describe the incident.',
            'description.min' => 'Describe the incident in at least 50 characters.',
            'confirm_accurate.accepted' => 'Confirm that the information is accurate.',
            'confirm_false.accepted' => 'Confirm that you understand false reporting causes harm.',
            'consent_processing.accepted' => 'Consent is required before this report can be received.',
            'evidence.max' => 'Upload no more than 5 evidence files.',
        ];
    }

    /**
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                foreach ($this->file('evidence', []) as $file) {
                    if (! $file instanceof UploadedFile) {
                        continue;
                    }

                    $isVideo = str_starts_with((string) $file->getMimeType(), 'video/')
                        || preg_match('/\.(mp4|mov|webm|avi)$/i', $file->getClientOriginalName()) === 1;
                    $limit = $isVideo ? 20 * 1024 * 1024 : 10 * 1024 * 1024;

                    if ($file->getSize() > $limit) {
                        $validator->errors()->add(
                            'evidence',
                            $isVideo
                                ? 'Each video must be 20 MB or smaller.'
                                : 'Each image or document must be 10 MB or smaller.',
                        );
                    }
                }
            },
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function reportDetails(): array
    {
        return $this->safe()->only([
            'full_name',
            'phone',
            'email',
            'alt_contact',
            'relationship',
            'incident_type',
            'incident_title',
            'incident_date',
            'incident_time',
            'state',
            'lga',
            'community',
            'full_address',
            'description',
            'victim_name',
            'victim_age',
            'victim_phone',
            'victim_last_location',
            'suspect_name',
            'suspect_alias',
            'suspect_description',
            'suspect_address',
            'suspect_vehicle',
            'suspect_notes',
            'incident_address',
            'latitude',
            'longitude',
            'victim_gender',
        ]);
    }

    /**
     * @return array<int, UploadedFile>
     */
    public function evidenceFiles(): array
    {
        $files = $this->file('evidence', []);

        return is_array($files) ? array_values($files) : [];
    }
}
