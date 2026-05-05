<?php

namespace Tests\Feature\Participant;

use App\Models\Currency;
use App\Models\Ltm\Auction;
use App\Models\Ltm\Bid;
use App\Models\Ltm\Carrier;
use App\Models\Ltm\Lot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParticipantFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_participant_cannot_access_ltm_routes(): void
    {
        $carrier = Carrier::create(['name' => 'Carrier A']);
        $user = User::factory()->create([
            'role' => 'Participant licitatii',
            'activ' => 1,
            'carrier_id' => $carrier->id,
        ]);

        $this->actingAs($user)
            ->get('/licitatii-transport-marfuri/panou')
            ->assertStatus(403);
    }

    public function test_participant_without_carrier_is_redirected_from_offers(): void
    {
        $user = User::factory()->create([
            'role' => 'Participant licitatii',
            'activ' => 1,
            'carrier_id' => null,
        ]);

        $this->actingAs($user)
            ->get('/participant/oferte')
            ->assertRedirect('/acasa');
    }

    public function test_participant_dashboard_counts_own_offers_with_status_distribution(): void
    {
        $carrier = Carrier::create(['name' => 'Carrier A']);
        $otherCarrier = Carrier::create(['name' => 'Carrier B']);
        $user = User::factory()->create([
            'role' => 'Participant licitatii',
            'activ' => 1,
            'carrier_id' => $carrier->id,
        ]);

        $auction = Auction::create([
            'auction_number' => 'LTM-OP-1',
            'title' => 'Open',
            'status' => 'deschisÄƒ',
        ]);
        $lotA = Lot::create(['auction_id' => $auction->id, 'code' => 'LOT-A']);
        $lotB = Lot::create(['auction_id' => $auction->id, 'code' => 'LOT-B']);
        $lotC = Lot::create(['auction_id' => $auction->id, 'code' => 'LOT-C']);
        $lotD = Lot::create(['auction_id' => $auction->id, 'code' => 'LOT-D']);

        Bid::create([
            'auction_id' => $auction->id,
            'lot_id' => $lotA->id,
            'carrier_id' => $carrier->id,
            'status' => 'trimisa',
        ]);
        Bid::create([
            'auction_id' => $auction->id,
            'lot_id' => $lotB->id,
            'carrier_id' => $carrier->id,
            'status' => 'trimisa',
        ]);
        Bid::create([
            'auction_id' => $auction->id,
            'lot_id' => $lotC->id,
            'carrier_id' => $carrier->id,
            'status' => 'acceptata',
        ]);
        Bid::create([
            'auction_id' => $auction->id,
            'lot_id' => $lotD->id,
            'carrier_id' => $otherCarrier->id,
            'status' => 'trimisa',
        ]);

        $this->actingAs($user)
            ->get('/acasa')
            ->assertOk()
            ->assertViewIs('participant.home')
            ->assertViewHas('stats', function (array $stats) {
                $statusTotals = collect($stats['participantMyBidStatusDistribution'])
                    ->pluck('total', 'status')
                    ->map(fn ($total) => (int) $total)
                    ->all();

                return ($stats['participantCarrierBidCount'] ?? null) === 3
                    && ($statusTotals['trimisa'] ?? null) === 2
                    && ($statusTotals['acceptata'] ?? null) === 1;
            });
    }

    public function test_participant_can_create_offer_only_for_open_auctions(): void
    {
        $carrier = Carrier::create(['name' => 'Carrier A']);
        $currency = Currency::where('code', 'EUR')->firstOrFail();
        $user = User::factory()->create([
            'role' => 'Participant licitatii',
            'activ' => 1,
            'carrier_id' => $carrier->id,
        ]);

        $closedAuction = Auction::create([
            'auction_number' => 'LTM-CL-1',
            'title' => 'Closed',
            'status' => 'atribuită',
        ]);
        $closedLot = Lot::create([
            'auction_id' => $closedAuction->id,
            'code' => 'LOT-1',
        ]);

        $this->actingAs($user)
            ->post('/participant/oferte', [
                'lot_id' => $closedLot->id,
                'price_per_trip_eur' => 100,
                'currency_id' => $currency->id,
            ])
            ->assertStatus(403);

        $openAuction = Auction::create([
            'auction_number' => 'LTM-OP-1',
            'title' => 'Open',
            'status' => 'deschisă',
        ]);
        $openLot = Lot::create([
            'auction_id' => $openAuction->id,
            'code' => 'LOT-2',
        ]);

        $this->actingAs($user)
            ->post('/participant/oferte', [
                'lot_id' => $openLot->id,
                'price_per_trip_eur' => 123.45,
                'currency_id' => $currency->id,
                'payment_terms_days' => 30,
            ])
            ->assertRedirect('/participant/oferte');

        $this->assertDatabaseHas('ltm_bids', [
            'carrier_id' => $carrier->id,
            'lot_id' => $openLot->id,
            'auction_id' => $openAuction->id,
        ]);
    }

    public function test_participant_sees_only_own_offers(): void
    {
        $carrierA = Carrier::create(['name' => 'Carrier A']);
        $carrierB = Carrier::create(['name' => 'Carrier B']);

        $userA = User::factory()->create([
            'role' => 'Participant licitatii',
            'activ' => 1,
            'carrier_id' => $carrierA->id,
        ]);

        $auction = Auction::create([
            'auction_number' => 'LTM-OP-1',
            'title' => 'Open',
            'status' => 'deschisă',
        ]);
        $lotA = Lot::create(['auction_id' => $auction->id, 'code' => 'LOT-A']);
        $lotB = Lot::create(['auction_id' => $auction->id, 'code' => 'LOT-B']);

        Bid::create([
            'auction_id' => $auction->id,
            'lot_id' => $lotA->id,
            'carrier_id' => $carrierA->id,
            'price_per_trip_eur' => 100,
        ]);
        Bid::create([
            'auction_id' => $auction->id,
            'lot_id' => $lotB->id,
            'carrier_id' => $carrierB->id,
            'price_per_trip_eur' => 200,
        ]);

        $response = $this->actingAs($userA)->get('/participant/oferte');

        $response->assertOk();
        $response->assertSee('LOT-A');
        $response->assertDontSee('LOT-B');
    }
}
