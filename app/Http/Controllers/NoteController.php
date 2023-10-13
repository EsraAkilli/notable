<?php

namespace App\Http\Controllers;

use App\Http\Requests\Note\DestroyNoteRequest;
use App\Http\Requests\Note\CreateNoteRequest;
use App\Http\Requests\Note\ListRequest;
use App\Http\Requests\Note\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Services\NoteService;
use App\Services\NoteListService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function list(ListRequest $request): JsonResponse
    {
        $user = $request->user();

        $notes = NoteListService::make($user)
            ->addFilter('title', $request->input('title'))
            ->addFilter('content', $request->input('content'))
            ->get();

        return api(
            NoteResource::collection($notes)
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
