<?php

namespace App\Http\Controllers;

use App\Http\Requests\Note\CreateNoteRequest;
use App\Http\Requests\Note\ListRequest;
use App\Http\Requests\Note\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Services\NoteListService;
use App\Services\NoteService;
use Illuminate\Http\JsonResponse;

class NoteController extends Controller
{
    public function list(ListRequest $request): JsonResponse
    {
        $user = $request->user();

        $service = NoteListService::make($user)
            ->addFilter('title', $request->input('title'))
            ->addFilter('content', $request->input('content'));

        return api(
            $service->result() // NoteResource::collection($notes)
        );
    }

    public function create(CreateNoteRequest $request): JsonResponse
    {
        $service = NoteService::make()
            ->setTitle($request->input('title'))
            ->setContent($request->input('content'))
            ->setUser(user());

        $service->create();

        return api(
            $service->resource(),
            201
        );
    }

    public function show(Note $note): JsonResponse
    {
        $note->loadMissing('user');

        return api(
            NoteResource::make($note)
        );
    }

    public function update(UpdateNoteRequest $request): JsonResponse
    {
        $service = NoteService::make()
            ->setTitle($request->input('title'))
            ->setContent($request->input('content'));

        $service->update();

        return api(
            $service->resource(),
        );
    }

    public function destroy(Note $note): JsonResponse
    {
        $service = NoteService::make()
            ->setNote($note);

        $service->destroy();

        return api(
            null
        );
    }
}
