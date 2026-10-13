<?php

namespace Tests\Feature;

use App\Models\DonationSetting;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_homepage_renders_the_foundation_index(): void
    {
        $this->get(route('home'))
            ->assertSee('Stand For Humanity Foundation')
            ->assertSee('Standing for Humanity. Protecting the Vulnerable.')
            ->assertSee('Owerri, Imo State')
            ->assertSee('Registration number 8133753')
            ->assertSee('13 December 2024')
            ->assertSee('32601482-0001')
            ->assertSee('images/cac-certificate.jpg', false)
            ->assertSee('id="cacCertificateModal"', false)
            ->assertSee(route('about'), false)
            ->assertDontSee('Charitize/about.html', false);
    }

    public function test_homepage_shows_the_administrator_donation_goal(): void
    {
        DonationSetting::factory()->create([
            'child_protection_goal' => 75000,
            'rescue_goal' => 40000,
            'victim_support_goal' => 25000,
        ]);

        $this->get(route('home'))
            ->assertSee('75,000')
            ->assertSee('40,000')
            ->assertSee('25,000')
            ->assertSee('data-donation-cause="child-protection"', false)
            ->assertSee('0%');
    }
}
