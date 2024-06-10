<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Note $note)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $comment = $note->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $validated['content'],
        ]);

        return response()->json($comment, 201);
    }

    public function index(Note $note)
    {
        $comments = $note->comments()->with('user')->get();
        return response()->json($comments);
    }
}
