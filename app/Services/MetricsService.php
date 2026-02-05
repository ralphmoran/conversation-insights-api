<?php

namespace App\Services;

use App\Enums\ConversationSource;
use App\Models\Conversation;
use Illuminate\Support\Facades\DB;

class MetricsService
{
    public function getMetrics(?string $startDate = null, ?string $endDate = null): array
    {
        $query = Conversation::query();

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        $totalCalls = $query->count();
        $totalConversions = (clone $query)->where('converted', true)->count();
        $missedOpportunities = $totalCalls - $totalConversions;
        $conversionRate = $totalCalls > 0 ? round($totalConversions / $totalCalls, 4) : 0;

        $avgDuration = (clone $query)->avg('duration_seconds') ?? 0;

        $bySource = $this->getMetricsBySource(clone $query);

        return [
            'total_calls' => $totalCalls,
            'total_conversions' => $totalConversions,
            'missed_opportunities' => $missedOpportunities,
            'conversion_rate' => $conversionRate,
            'avg_duration_seconds' => round($avgDuration),
            'by_source' => $bySource,
        ];
    }

    private function getMetricsBySource($query): array
    {
        $sourceStats = $query
            ->select('source')
            ->selectRaw('COUNT(*) as calls')
            ->selectRaw('SUM(CASE WHEN converted = 1 THEN 1 ELSE 0 END) as conversions')
            ->selectRaw('AVG(duration_seconds) as avg_duration')
            ->groupBy('source')
            ->get();

        $bySource = [];

        foreach ($sourceStats as $stat) {
            $source = $stat->source instanceof ConversationSource
                ? $stat->source->value
                : $stat->source;

            $bySource[$source] = [
                'calls' => (int) $stat->calls,
                'conversions' => (int) $stat->conversions,
                'rate' => $stat->calls > 0
                    ? round($stat->conversions / $stat->calls, 4)
                    : 0,
                'avg_duration_seconds' => round($stat->avg_duration ?? 0),
            ];
        }

        return $bySource;
    }
}
