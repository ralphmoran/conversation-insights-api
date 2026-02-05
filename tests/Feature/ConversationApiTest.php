<?php

namespace Tests\Feature;

use App\Enums\ConversationSource;
use App\Models\Conversation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConversationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_conversations(): void
    {
        Conversation::factory()->count(5)->create();

        $response = $this->getJson('/api/conversations');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'caller_name',
                        'phone_number',
                        'source',
                        'transcript',
                        'converted',
                        'conversion_reason',
                        'duration_seconds',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    }

    public function test_can_filter_conversations_by_source(): void
    {
        Conversation::factory()->fromSource(ConversationSource::GOOGLE_ADS)->count(3)->create();
        Conversation::factory()->fromSource(ConversationSource::WEBSITE)->count(2)->create();

        $response = $this->getJson('/api/conversations?source=google_ads');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_filter_conversations_by_converted(): void
    {
        Conversation::factory()->converted()->count(2)->create();
        Conversation::factory()->notConverted()->count(3)->create();

        $response = $this->getJson('/api/conversations?converted=true');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_can_create_conversation(): void
    {
        $data = [
            'caller_name' => 'John Doe',
            'phone_number' => '555-1234',
            'source' => 'google_ads',
            'transcript' => 'Hi, I am interested in your services...',
            'converted' => true,
            'conversion_reason' => 'Scheduled appointment',
            'duration_seconds' => 180,
        ];

        $response = $this->postJson('/api/conversations', $data);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'caller_name' => 'John Doe',
                'source' => 'google_ads',
                'converted' => true,
            ]);

        $this->assertDatabaseHas('conversations', [
            'caller_name' => 'John Doe',
            'source' => 'google_ads',
        ]);
    }

    public function test_create_conversation_validates_required_fields(): void
    {
        $response = $this->postJson('/api/conversations', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['caller_name', 'source', 'transcript']);
    }

    public function test_create_conversation_validates_source_enum(): void
    {
        $data = [
            'caller_name' => 'John Doe',
            'source' => 'invalid_source',
            'transcript' => 'Test transcript',
        ];

        $response = $this->postJson('/api/conversations', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['source']);
    }

    public function test_can_show_single_conversation(): void
    {
        $conversation = Conversation::factory()->create([
            'caller_name' => 'Jane Smith',
        ]);

        $response = $this->getJson("/api/conversations/{$conversation->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'caller_name' => 'Jane Smith',
            ]);
    }

    public function test_can_delete_conversation(): void
    {
        $conversation = Conversation::factory()->create();

        $response = $this->deleteJson("/api/conversations/{$conversation->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('conversations', ['id' => $conversation->id]);
    }
}
