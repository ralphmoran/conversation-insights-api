<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConversationRequest;
use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ConversationController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Conversation::query();

        if ($request->has('source')) {
            $query->where('source', $request->input('source'));
        }

        if ($request->has('converted')) {
            $query->where('converted', filter_var($request->input('converted'), FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->has('start_date')) {
            $query->where('created_at', '>=', $request->input('start_date'));
        }

        if ($request->has('end_date')) {
            $query->where('created_at', '<=', $request->input('end_date'));
        }

        $conversations = $query
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 15));

        return ConversationResource::collection($conversations);
    }

    public function store(StoreConversationRequest $request): ConversationResource
    {
        $conversation = Conversation::create($request->validated());

        return new ConversationResource($conversation);
    }

    public function show(Conversation $conversation): ConversationResource
    {
        return new ConversationResource($conversation);
    }

    public function destroy(Conversation $conversation): JsonResponse
    {
        $conversation->delete();

        return response()->json(['message' => 'Conversation deleted'], 200);
    }
}
