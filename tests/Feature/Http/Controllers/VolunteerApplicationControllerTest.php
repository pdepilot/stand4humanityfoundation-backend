<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\VolunteerInterest;
use App\Enums\VolunteerSource;
use App\Enums\VolunteerStatus;
use App\Models\VolunteerApplication;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class VolunteerApplicationControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_volunteer_page_includes_csrf_token_and_capture_script(): void
    {
        $this->get(route('volunteer'))
            ->assertSee('name="csrf-token"', false)
            ->assertSee('Charitize/js/volunteer-capture.js', false)
            ->assertSee('id="volunteerCaptureForm"', false)
            ->assertSee('enctype="multipart/form-data"', false)
            ->assertSee('id="volunteerPhoto"', false)
            ->assertSee('/api/volunteers', false);
    }

    public function test_empty_payload_returns_422_with_required_field_messages(): void
    {
        $this->post(route('volunteers.api.store'), [], ['Accept' => 'application/json'])
            ->assertUnprocessable()
            ->assertJsonPath('errors.name.0', 'Enter your name.')
            ->assertJsonPath('errors.email.0', 'Enter your email address.')
            ->assertJsonPath('errors.phone.0', 'Enter your phone number.')
            ->assertJsonPath('errors.city.0', 'Enter your city or community.')
            ->assertJsonPath('errors.interest.0', 'Choose the area you want to support.')
            ->assertJsonPath('errors.availability.0', 'Tell us when you can serve.')
            ->assertJsonPath('errors.motivation.0', 'Tell us why you want to volunteer.')
            ->assertJsonPath('errors.photo.0', 'Upload a clear photo of yourself.');

        $this->assertDatabaseCount('volunteer_applications', 0);
    }

    public function test_honeypot_payload_is_rejected_without_saving(): void
    {
        Storage::fake('public');

        $this->post(route('volunteers.api.store'), $this->validPayload([
            'website' => 'https://spam.test',
        ]), ['Accept' => 'application/json'])
            ->assertUnprocessable()
            ->assertJsonPath('errors.website.0', 'Unable to accept this application.');

        $this->assertDatabaseCount('volunteer_applications', 0);
    }

    public function test_valid_payload_creates_a_pending_public_application(): void
    {
        $disk = Storage::fake('public');

        $response = $this->post(route('volunteers.api.store'), $this->validPayload(), [
            'Accept' => 'application/json',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('ok', true)
            ->assertJsonMissingPath('volunteer.name')
            ->assertJsonMissingPath('volunteer.email')
            ->assertJsonMissingPath('volunteer.motivation');

        $application = VolunteerApplication::query()->first();

        $this->assertNotNull($application);
        $this->assertTrue(Str::startsWith($application->reference, 'vol-'));
        $this->assertSame($application->reference, $response->json('volunteer.reference'));
        $this->assertSame(VolunteerSource::Public, $application->source);
        $this->assertSame(VolunteerStatus::Pending, $application->status);
        $this->assertSame(VolunteerInterest::ChildProtection, $application->interest);
        $this->assertSame('Ada Obi', $application->name);
        $this->assertSame('ada@example.com', $application->email);
        $this->assertSame('08012345678', $application->phone);
        $this->assertSame('Owerri', $application->city);
        $this->assertTrue(Str::startsWith($application->photo, 'volunteers/'));
        $disk->assertExists($application->photo);
    }

    public function test_unexpected_status_reference_and_source_keys_are_ignored(): void
    {
        Storage::fake('public');

        $this->post(route('volunteers.api.store'), $this->validPayload([
            'status' => 'approved',
            'reference' => 'vol-hacked00001',
            'source' => 'desk',
        ]), ['Accept' => 'application/json'])
            ->assertCreated();

        $application = VolunteerApplication::query()->first();

        $this->assertNotNull($application);
        $this->assertSame(VolunteerStatus::Pending, $application->status);
        $this->assertSame(VolunteerSource::Public, $application->source);
        $this->assertNotSame('vol-hacked00001', $application->reference);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Ada Obi',
            'email' => 'ada@example.com',
            'phone' => '08012345678',
            'city' => 'Owerri',
            'interest' => VolunteerInterest::ChildProtection->value,
            'availability' => 'Weekends',
            'experience' => 'Youth mentoring in my community.',
            'motivation' => 'I want to stand with children and families who need a steady helping hand.',
            'photo' => UploadedFile::fake()->image('ada.jpg', 600, 600),
        ], $overrides);
    }
}
