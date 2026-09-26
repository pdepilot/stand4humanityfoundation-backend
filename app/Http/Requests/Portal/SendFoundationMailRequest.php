<?php

namespace App\Http\Requests\Portal;

use App\Enums\MailAudience;
use App\Enums\MailTemplate;
use App\Models\OutboundMessage;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class SendFoundationMailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', OutboundMessage::class) ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        return [
            'template' => ['required', Rule::enum(MailTemplate::class)],
            'audience' => ['required', Rule::enum(MailAudience::class)],
            'recipient_name' => ['nullable', 'string', 'max:120'],
            'recipient_email' => ['required_if:audience,individual', 'nullable', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:200'],
            'body' => ['required', 'string', 'max:5000'],
            'attachments' => ['nullable', 'array', 'max:3'],
            'attachments.*' => ['file', 'mimes:pdf,doc,docx,jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'template.required' => 'Choose a message template.',
            'audience.required' => 'Choose who should receive the message.',
            'recipient_email.required_if' => 'Enter the recipient email.',
            'recipient_email.email' => 'Enter a valid email address.',
            'subject.required' => 'Enter a subject line.',
            'body.required' => 'Write the message.',
            'attachments.max' => 'Attach no more than three documents.',
            'attachments.*.mimes' => 'Attach a PDF, Word document, or image.',
            'attachments.*.max' => 'Each document must be 5 MB or smaller.',
        ];
    }

    public function template(): MailTemplate
    {
        return MailTemplate::from((string) $this->validated('template'));
    }

    public function audience(): MailAudience
    {
        return MailAudience::from((string) $this->validated('audience'));
    }

    /**
     * @return list<UploadedFile>
     */
    public function documents(): array
    {
        $files = $this->file('attachments', []);

        if (! is_array($files)) {
            return [];
        }

        return array_values(array_filter(
            $files,
            fn (mixed $file): bool => $file instanceof UploadedFile,
        ));
    }
}
