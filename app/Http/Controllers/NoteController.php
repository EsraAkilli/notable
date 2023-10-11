<?php

namespace App\Http\Controllers;

use App\Http\Requests\Note\DestroyNoteRequest;
use App\Http\Requests\Note\CreateNoteRequest;
use App\Http\Requests\Note\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Services\NoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $notes = $user->notes()
            ->orderBy('updated_at', 'DESC')
            ->get();

        return api(
            NoteResource::collection($notes)
        );
    }

    public function create(CreateNoteRequest $request): JsonResponse
    {
        $service = NoteService::make()
            ->setTitle($request->input('title'));

        $service->create();

        $note = Note::query()->create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'user_id' => auth()->id(),
        ]);

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

    public function update(UpdateNoteRequest $request, Note $note): JsonResponse
    {
        $note = Note::query()->update([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ]);

        return api(
            NoteResource::make($note)
        );
    }

    public function destroy(DestroyNoteRequest $request, Note $note): JsonResponse
    {
        $note->delete();

        return api(
            null,
            204
        );
    }
}
