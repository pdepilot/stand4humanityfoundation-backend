<?php

namespace Tests\Feature;

use App\Enums\TestimonyStatus;
use App\Models\Testimony;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TestimonyControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_the_homepage_offers_a_testimony_form(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Give testimony')
            ->assertSee('id="giveTestimonyButton"', false)
            ->assertSee('id="give-testimony" class="d-none" hidden', false)
            ->assertSee('id="testimonyCaptureForm"', false)
            ->assertSee('Charitize/js/testimony-capture.js?v=reveal', false)
            ->assertSee('/api/testimonies', false)
            ->assertSee('Approved testimonies will appear here.');
    }

    public function test_empty_payload_returns_the_required_messages(): void
    {
        $this->postJson(route('testimonies.api.store'), [])
            ->assertUnprocessable()
            ->assertJsonPath('errors.name.0', 'Enter your name.')
            ->assertJsonPath('errors.body.0', 'Write your testimony.')
            ->assertJsonPath('errors.rating.0', 'Choose a rating.');
    }

    public function test_a_public_testimony_waits_for_review(): void
    {
        $disk = Storage::fake('public');

        $this->postJson(route('testimonies.api.store'), [
            'name' => 'Ada Obi',
            'email' => 'ada@example.com',
            'role' => 'Parent',
            'body' => 'The foundation stood with our family when we had nowhere else to turn.',
            'rating' => 5,
            'photo' => UploadedFile::fake()->image('ada.jpg'),
        ])
            ->assertCreated()
            ->assertJsonPath('message', 'Thank you. Your testimony is waiting for review.');

        $testimony = Testimony::query()->first();

        $this->assertNotNull($testimony);
        $this->assertSame(TestimonyStatus::Pending, $testimony->status);
        $this->assertSame('ada@example.com', $testimony->email);
        $disk->assertExists($testimony->photo);

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('The foundation stood with our family when we had nowhere else to turn.')
            ->assertDontSee('ada@example.com');
    }

    public function test_an_approved_testimony_appears_on_the_homepage(): void
    {
        Testimony::factory()->approved()->create([
            'name' => 'Ada Obi',
            'email' => 'ada@example.com',
            'body' => 'The foundation stood with our family when we had nowhere else to turn.',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Ada Obi')
            ->assertSee('The foundation stood with our family when we had nowhere else to turn.')
            ->assertDontSee('ada@example.com');
    }

    public function test_the_homepage_escapes_a_testimony(): void
    {
        Testimony::factory()->approved()->create([
            'name' => "<script>alert('xss')</script>",
            'body' => 'The foundation stood with our family when we had nowhere else to turn.',
        ]);

        $this->get(route('home'))
            ->assertDontSee("<script>alert('xss')</script>", false)
            ->assertSee('&lt;script&gt;', false);
    }
}
