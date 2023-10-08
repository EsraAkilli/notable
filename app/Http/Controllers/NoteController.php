<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoteRequests\DestroyNoteRequest;
use App\Http\Requests\NoteRequests\CreateNoteRequest;
use App\Http\Requests\NoteRequests\ShowNoteRequest;
use App\Http\Requests\NoteRequests\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        $note = Note::query()->create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ]);

        return api(
            NoteResource::make($note),
            201
        );
    }

    public function show(ShowNoteRequest $request, Note $note): JsonResponse
    {
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
