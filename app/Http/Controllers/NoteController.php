<?php

namespace App\Http\Controllers;

use App\Http\Requests\Note\CreateNoteRequest;
use App\Http\Requests\Note\ListRequest;
use App\Http\Requests\Note\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Comment;
use App\Models\Note;
use App\Services\NoteListService;
use App\Services\NoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function list(ListRequest $request)
    {
        $user = $request->user();

        if ($request->boolean('is_public')) {
            $user = null;
        }

        $service = NoteListService::make($user)
            ->search($request->input('query'));

        return $service->result();
    }

    public function create(CreateNoteRequest $request): JsonResponse
    {
        $service = NoteService::make()
            ->setTitle($request->input('title'))
            ->setContent($request->input('content'))
            ->setUser(user());

        $service->create();
        $service->addTags($request->input('tags', []));

        return api(
            $service->resource(),
            201
        );
    }

    public function show(Note $note): JsonResponse
    {
        $note->loadMissing(['user', 'tags']);

        return api(
            NoteResource::make($note)
        );
    }

    public function update(Note $note, UpdateNoteRequest $request): JsonResponse
    {
        $service = NoteService::make()
            ->setNote($note)
            ->setTitle($request->input('title'))
            ->setContent($request->input('content'));

        $service->addTags($request->input('tags', []));

        $service->update();

        return api([]);
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

    public function like(Request $request, Note $note): JsonResponse
    {
        $user = $request->user();
        $likeCount = $note->likes()->count();

        if ($note->likes()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Already liked', 'likeCount' => $likeCount], 400);
        }

        $note->dislikes()->where('user_id', $user->id)->first()?->delete();

        $note->likes()->create([
            'user_id' => $user->id,
        ]);


        return response()->json(['message' => 'Liked', 'likeCount' => $likeCount]);
    }

    public function dislike(Request $request, Note $note): JsonResponse
    {
        $user = $request->user();

        if ($note->dislikes()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Already disliked'], 400);
        }

        $note->likes()->where('user_id', $user->id)->first()?->delete();

        $note->dislikes()->create([
            'user_id' => $user->id,
        ]);

        $dislikeCount = $note->dislikes()->count();
        return response()->json(['message' => 'Disliked', 'dislikeCount' => $dislikeCount]);
    }

    public function addComment(Request $request, Note $note): JsonResponse
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $comment = new Comment();
        $comment->note_id = $note->id;
        $comment->user_id = $request->user()->id;
        $comment->content = $request->input('content');
        $comment->save();

        return response()->json(['message' => 'Comment added successfully', 'comment' => $comment], 201);
    }

    public function getComments($id): JsonResponse
    {
        $comments = Comment::query()->where('note_id', $id)->with('user')->get();

        return response()->json(['comments' => $comments], 200);
    }
}
