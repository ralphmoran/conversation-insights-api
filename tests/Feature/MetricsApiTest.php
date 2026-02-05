<?php

namespace Tests\Feature;

use App\Enums\ConversationSource;
use App\Models\Conversation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MetricsApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_metrics_returns_correct_structure(): void
    {
        Conversation::factory()->count(5)->create();

        $response = $this->getJson('/api/metrics');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'total_calls',
                    'total_conversions',
                    'missed_opportunities',
                    'conversion_rate',
                    'avg_duration_seconds',
                    'by_source',
                ],
            ]);
    }

    public function test_metrics_calculates_totals_correctly(): void
    {
        Conversation::factory()->converted()->count(3)->create();
        Conversation::factory()->notConverted()->count(7)->create();

        $response = $this->getJson('/api/metrics');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'total_calls' => 10,
                'total_conversions' => 3,
                'missed_opportunities' => 7,
                'conversion_rate' => 0.3,
            ]);
    }

    public function test_metrics_groups_by_source(): void
    {
        Conversation::factory()
            ->fromSource(ConversationSource::GOOGLE_ADS)
            ->converted()
            ->count(2)
            ->create();

        Conversation::factory()
            ->fromSource(ConversationSource::GOOGLE_ADS)
            ->notConverted()
            ->count(3)
            ->create();

        Conversation::factory()
            ->fromSource(ConversationSource::WEBSITE)
            ->converted()
            ->count(1)
            ->create();

        $response = $this->getJson('/api/metrics');

        $response->assertStatus(200);

        $data = $response->json('data');

        $this->assertEquals(5, $data['by_source']['google_ads']['calls']);
        $this->assertEquals(2, $data['by_source']['google_ads']['conversions']);
        $this->assertEquals(0.4, $data['by_source']['google_ads']['rate']);

        $this->assertEquals(1, $data['by_source']['website']['calls']);
        $this->assertEquals(1, $data['by_source']['website']['conversions']);
        $this->assertEquals(1.0, $data['by_source']['website']['rate']);
    }

    public function test_metrics_handles_empty_database(): void
    {
        $response = $this->getJson('/api/metrics');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'total_calls' => 0,
                'total_conversions' => 0,
                'missed_opportunities' => 0,
                'conversion_rate' => 0,
            ]);
    }

    public function test_metrics_calculates_average_duration(): void
    {
        Conversation::factory()->create(['duration_seconds' => 100]);
        Conversation::factory()->create(['duration_seconds' => 200]);
        Conversation::factory()->create(['duration_seconds' => 300]);

        $response = $this->getJson('/api/metrics');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'avg_duration_seconds' => 200,
            ]);
    }
}
