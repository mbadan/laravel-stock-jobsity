<?php 

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\StockHistory;

class StockApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_stock_data_for_valid_symbol()
    {
        $response = $this->getJson('/api/stock?q=aapl');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'symbol',
                     'name',
                     'open',
                     'high',
                     'low',
                     'close',
                 ]);
    }

    /** @test */
    public function it_returns_stock_history()
    {
        // Create some dummy history
        StockHistory::factory()->count(2)->create([
            'symbol' => 'AAPL',
        ]);

        $response = $this->getJson('/api/history');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id', 'symbol', 'name', 'open', 'high', 'low', 'close', 'created_at', 'updated_at']
                 ]);
    }
}
