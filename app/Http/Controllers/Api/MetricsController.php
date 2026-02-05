<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MetricsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MetricsController extends Controller
{
    public function __construct(
        private readonly MetricsService $metricsService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $metrics = $this->metricsService->getMetrics(
            $request->input('start_date'),
            $request->input('end_date')
        );

        return response()->json([
            'data' => $metrics,
        ]);
    }
}
