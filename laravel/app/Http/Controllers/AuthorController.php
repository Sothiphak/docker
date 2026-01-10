<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Author;
use Illuminate\Support\Facades\Hash;

class AuthorController extends Controller
{
    public function store(Request $request)
    {
        $user = User::create([
            'name' => $request->username,
            'email' => $request->username . '@example.com',
            'password' => Hash::make('password'),
        ]);

        $author = $user->author()->create([
            'name' => $request->name 
        ]);

        return response()->json(['message' => 'Author created', 'data' => $author], 201);
    }
}
