<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\User;
use App\Models\Article;
use App\Models\Author;
use App\Models\Audience;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $user = User::where('name', $request->username)->firstOrFail();

        $model = null;
        if ($request->type === 'article') {
            $model = Article::where('name', $request->target_name)->firstOrFail();
        }
        elseif ($request->type === 'author') {
            $model = Author::where('name', $request->target_name)->firstOrFail();
        }
        elseif ($request->type === 'audience') {
            $model = Audience::where('name', $request->target_name)->firstOrFail();
        }

        $model->comments()->create([
            'name' => $request->content,
            'user_id' => $user->id
        ]);

        return response()->json(['message' => 'Comment added'], 201);
    }

    public function getByAudienceName($name)
    {
        $user = User::where('name', $name)->firstOrFail();
        return response()->json($user->comments);
    }

    public function getAllWithTopic()
    {
        $comments = Comment::with('commentable')->get();
        return response()->json($comments);
    }
}
