<?php

namespace Database\Seeders;

use App\Enums\ConversationSource;
use App\Models\Conversation;
use Illuminate\Database\Seeder;

class ConversationSeeder extends Seeder
{
    public function run(): void
    {
        // Create a realistic distribution of conversations
        $sources = [
            ['source' => ConversationSource::GOOGLE_ADS, 'count' => 40],
            ['source' => ConversationSource::WEBSITE, 'count' => 30],
            ['source' => ConversationSource::REFERRAL, 'count' => 15],
            ['source' => ConversationSource::DIRECT, 'count' => 10],
            ['source' => ConversationSource::SOCIAL, 'count' => 5],
        ];

        foreach ($sources as $item) {
            $source = $item['source'];
            $count = $item['count'];

            // 40% conversion rate for most sources
            $convertedCount = (int) ($count * 0.4);
            $notConvertedCount = $count - $convertedCount;

            Conversation::factory()
                ->fromSource($source)
                ->converted()
                ->count($convertedCount)
                ->create();

            Conversation::factory()
                ->fromSource($source)
                ->notConverted()
                ->count($notConvertedCount)
                ->create();
        }
    }
}
