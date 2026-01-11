<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Audience;
use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Facades\Hash;

class AudienceController extends Controller
{
    public function createUser(Request $request)
    {
        $user = User::create([
            'name' => $request->username,
            'email' => $request->username . '@example.com',
            'password' => Hash::make('password'),
        ]);

        return response()->json(['message' => "User {$user->name} created"], 201);
    }

    public function subscribe(Request $request)
    {
        $user = User::where('name', $request->username)->firstOrFail();

        $article = Article::where('name', $request->article_name)->firstOrFail();

        $audience = Audience::create([
            'name' => $user->name,
            'user_id' => $user->id,
            'article_id' => $article->id
        ]);

        return response()->json(['message' => "Subscribed to {$article->name}"], 201);
    }

    public function getByArticle(Request $request)
    {
        $articleName = urldecode($request->route('name'));

        $audiences = Audience::whereHas('article', function ($query) use ($articleName) {
            $query->where('name', $articleName);
        })->with('user')->get();

        return response()->json($audiences);
    }
}
