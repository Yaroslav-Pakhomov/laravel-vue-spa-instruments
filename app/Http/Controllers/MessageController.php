<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\StoreMessageEvent;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Http\Resources\Message\MessageResource;
use App\Models\Message;
use Inertia\Response;
use Inertia\ResponseFactory;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response|ResponseFactory
     */
    public function index(): Response|ResponseFactory
    {
        $messages = Message::getAllMessages();
        $messages = MessageResource::collection($messages)->resolve();

        return inertia('Message/Index', compact('messages'));
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
    public function store(StoreMessageRequest $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeAsync(StoreMessageRequest $request): array
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        $message = Message::query()->create($validated);

        // Подключение события создания сообщения
        event(new StoreMessageEvent($message));

        return MessageResource::make($message)->resolve();
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMessageRequest $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Message $message)
    {
        //
    }
}
