<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageCreated;
use App\Models\ChatMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ChatMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Chat/Index', [
            'chatMessages' => ChatMessage::with('user:id,name')->latest()->get(),
            'can' => [
                'manageChatMessages' => auth()->user()->can('manage chat messages'),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $chatMessage = $request->user()->chatMessages()->create($validated);

        broadcast(new ChatMessageCreated($chatMessage))->toOthers();

        return redirect(route('chat.index'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChatMessage $chatMessage): RedirectResponse
    {
        Gate::authorize('update', $chatMessage);

        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $chatMessage->update($validated);

        return redirect(route('chat.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChatMessage $chatMessage): RedirectResponse
    {
        Gate::authorize('delete', $chatMessage);

        $chatMessage->delete();

        return redirect(route('chat.index'));
    }
}
