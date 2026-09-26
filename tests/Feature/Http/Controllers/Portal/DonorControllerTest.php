<?php

namespace Tests\Feature\Http\Controllers\Portal;

use App\Enums\DonationPaymentMethod;
use App\Enums\DonationSource;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DonorControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('portal.donors.index'))
            ->assertRedirect(route('portal.login'));
    }

    public function test_staff_are_forbidden_from_the_donor_desk(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->get(route('portal.donors.index'))
            ->assertForbidden();
    }

    public function test_administrator_sees_the_empty_donor_ledger(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->get(route('portal.donors.index'))
            ->assertOk()
            ->assertSee('Donor ledger')
            ->assertSee('No donors on the desk yet.')
            ->assertSee('Record a gift');
    }

    public function test_administrator_sees_public_and_desk_gifts_on_the_ledger(): void
    {
        $administrator = User::factory()->administrator()->create();
        Donation::factory()->create([
            'name' => 'Ada Obi',
            'email' => 'ada@example.com',
            'amount' => 5000,
        ]);
        Donation::factory()->fromDesk()->create([
            'name' => 'Desk Gift',
            'email' => 'desk@example.com',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.donors.index'))
            ->assertOk()
            ->assertSee('Ada Obi')
            ->assertSee('ada@example.com')
            ->assertSee('Desk Gift')
            ->assertSee('Public donate page')
            ->assertSee('Command desk')
            ->assertSee('2 gifts');
    }

    public function test_administrator_sees_the_legal_name_of_an_anonymous_donor(): void
    {
        $administrator = User::factory()->administrator()->create();
        Donation::factory()->anonymous()->create([
            'name' => 'Ada Obi',
            'email' => 'ada@example.com',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.donors.index'))
            ->assertOk()
            ->assertSee('Ada Obi')
            ->assertSee('ada@example.com')
            ->assertSee('Anonymous on the public ledger');
    }

    public function test_donor_ledger_escapes_dangerous_content(): void
    {
        $administrator = User::factory()->administrator()->create();
        Donation::factory()->create([
            'name' => "<script>alert('xss')</script>",
            'email' => 'safe@example.com',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.donors.index'))
            ->assertDontSee("<script>alert('xss')</script>", false)
            ->assertSee('&lt;script&gt;', false);
    }

    public function test_administrator_can_view_the_record_form(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->get(route('portal.donors.create'))
            ->assertOk()
            ->assertSee('Record a gift')
            ->assertSee('Donation details')
            ->assertSee('value="cash"', false)
            ->assertSee('Cash')
            ->assertSee('value="transfer"', false)
            ->assertSee('Transfer');
    }

    public function test_staff_cannot_submit_a_desk_donation(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->post(route('portal.donors.store'), $this->validPayload())
            ->assertForbidden();

        $this->assertDatabaseCount('donations', 0);
    }

    public function test_empty_desk_payload_returns_validation_errors(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.donors.create'))
            ->post(route('portal.donors.store'), [])
            ->assertRedirect(route('portal.donors.create'))
            ->assertSessionHasErrors([
                'name' => 'Enter the donor name.',
                'email' => 'Enter the donor email.',
                'phone' => 'Enter the donor phone number.',
                'amount' => 'Enter a donation amount.',
                'cause' => 'Choose a donation cause.',
            ]);

        $this->assertDatabaseCount('donations', 0);
    }

    public function test_administrator_can_record_a_desk_donation(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.donors.create'))
            ->post(route('portal.donors.store'), $this->validPayload())
            ->assertRedirectToRoute('portal.donors.show', Donation::query()->first())
            ->assertSessionHas('status', 'The donation is on the ledger.');

        $donation = Donation::query()->first();

        $this->assertNotNull($donation);
        $this->assertSame(DonationSource::Desk, $donation->source);
        $this->assertSame('Ngozi Eze', $donation->name);
        $this->assertSame('ngozi@example.com', $donation->email);
        $this->assertSame('25000.00', $donation->amount);
        $this->assertFalse($donation->anonymous);
    }

    #[DataProvider('deskPaymentMethods')]
    public function test_administrator_can_record_a_cash_or_transfer_gift(string $method, string $label): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.donors.create'))
            ->post(route('portal.donors.store'), $this->validPayload([
                'payment_method' => $method,
            ]))
            ->assertRedirectToRoute('portal.donors.show', Donation::query()->first());

        $donation = Donation::query()->first();

        $this->assertNotNull($donation);
        $this->assertSame(DonationPaymentMethod::from($method), $donation->payment_method);

        $this->actingAs($administrator)
            ->get(route('portal.donors.show', $donation))
            ->assertSee($label);
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function deskPaymentMethods(): array
    {
        return [
            'cash' => ['cash', 'Cash'],
            'transfer' => ['transfer', 'Transfer'],
        ];
    }

    public function test_desk_source_and_reference_keys_are_ignored(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->post(route('portal.donors.store'), $this->validPayload([
                'source' => 'public',
                'reference' => 'don-hacked00001',
            ]))
            ->assertRedirect();

        $donation = Donation::query()->first();

        $this->assertNotNull($donation);
        $this->assertSame(DonationSource::Desk, $donation->source);
        $this->assertNotSame('don-hacked00001', $donation->reference);
    }

    public function test_administrator_can_view_a_donor_record(): void
    {
        $administrator = User::factory()->administrator()->create();
        $donation = Donation::factory()->anonymous()->create([
            'name' => 'Ada Obi',
            'email' => 'ada@example.com',
            'phone' => '08012345678',
            'location' => 'Owerri, Imo State',
            'description' => 'For rescue work',
            'reference' => 'don-show0000001',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.donors.show', $donation))
            ->assertOk()
            ->assertSee('Ada Obi')
            ->assertSee('ada@example.com')
            ->assertSee('08012345678')
            ->assertSee('Owerri, Imo State')
            ->assertSee('For rescue work')
            ->assertSee('don-show0000001')
            ->assertSee('Anonymous Donor')
            ->assertSee('Remove record');
    }

    public function test_donor_record_escapes_dangerous_content(): void
    {
        $administrator = User::factory()->administrator()->create();
        $donation = Donation::factory()->create([
            'name' => "<script>alert('xss')</script>",
            'email' => 'safe@example.com',
            'location' => '<img src=x onerror=alert(1)>',
            'description' => '<svg onload=alert(1)>',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.donors.show', $donation))
            ->assertDontSee("<script>alert('xss')</script>", false)
            ->assertDontSee('<img src=x onerror=alert(1)>', false)
            ->assertDontSee('<svg onload=alert(1)>', false)
            ->assertSee('&lt;script&gt;', false);
    }

    public function test_staff_cannot_view_a_donor_record(): void
    {
        $staff = User::factory()->create();
        $donation = Donation::factory()->create();

        $this->actingAs($staff)
            ->get(route('portal.donors.show', $donation))
            ->assertForbidden();
    }

    public function test_administrator_can_remove_a_donor_record(): void
    {
        $administrator = User::factory()->administrator()->create();
        $donation = Donation::factory()->create();

        $this->actingAs($administrator)
            ->from(route('portal.donors.show', $donation))
            ->delete(route('portal.donors.destroy', $donation))
            ->assertRedirectToRoute('portal.donors.index')
            ->assertSessionHas('status', 'The donor record was removed.');

        $this->assertModelMissing($donation);
    }

    public function test_staff_cannot_remove_a_donor_record(): void
    {
        $staff = User::factory()->create();
        $donation = Donation::factory()->create();

        $this->actingAs($staff)
            ->delete(route('portal.donors.destroy', $donation))
            ->assertForbidden();

        $this->assertModelExists($donation);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Ngozi Eze',
            'email' => 'ngozi@example.com',
            'phone' => '08098765432',
            'location' => 'Owerri, Imo State',
            'description' => 'Desk entry',
            'amount' => 25000,
            'currency' => 'NGN',
            'cause' => 'general',
            'frequency' => 'one-time',
            'payment_method' => 'paystack',
        ], $overrides);
    }
}
