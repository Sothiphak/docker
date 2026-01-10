<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Author;

class ArticleController extends Controller
{
    public function store(Request $request)
    {
        $author = Author::where('name', $request->author_name)->firstOrFail();

        $article = $author->articles()->create([
            'name' => $request->name
        ]);

        return response()->json(['message' => 'Article created', 'data' => $article], 201);
    }

    public function getByAuthor($authorName)
    {
        $articles = Article::whereHas('author', function($query) use ($authorName) {
            $query->where('name', $authorName);
        })->get();

        return response()->json($articles);
    }
}
