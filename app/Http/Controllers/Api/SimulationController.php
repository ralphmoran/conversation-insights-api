<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SimulationController extends Controller
{
    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'count' => ['required', 'integer', 'min:1', 'max:100'],
            'conversion_rate' => ['sometimes', 'numeric', 'min:0', 'max:1'],
        ]);

        $count = $request->input('count');
        $conversionRate = $request->input('conversion_rate', 0.4);

        $convertedCount = (int) round($count * $conversionRate);
        $notConvertedCount = $count - $convertedCount;

        $conversations = collect();

        if ($convertedCount > 0) {
            $converted = Conversation::factory()
                ->converted()
                ->count($convertedCount)
                ->create();
            $conversations = $conversations->merge($converted);
        }

        if ($notConvertedCount > 0) {
            $notConverted = Conversation::factory()
                ->notConverted()
                ->count($notConvertedCount)
                ->create();
            $conversations = $conversations->merge($notConverted);
        }

        return response()->json([
            'message' => "Generated {$count} conversations",
            'created' => [
                'total' => $count,
                'converted' => $convertedCount,
                'not_converted' => $notConvertedCount,
            ],
        ]);
    }

    public function reset(): JsonResponse
    {
        $deleted = Conversation::count();
        Conversation::truncate();

        return response()->json([
            'message' => "Deleted all conversations",
            'deleted' => $deleted,
        ]);
    }
}
