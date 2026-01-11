<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AudienceController;
use App\Http\Controllers\CommentController;

// --- LOGIN ROUTE (Public) ---
Route::post('/login', function (Request $request) {
    // 1. Validate inputs
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // 2. Attempt Login
    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // 3. Generate Token
    $user = Auth::user();
    /** @var \App\Models\User $user */
    $token = $user->createToken('API Token')->accessToken;

    return response()->json([
        'user' => $user,
        'token' => $token,
    ]);
});

// --- Protected "Me" Route ---
Route::middleware('auth:api')->get('/me', function (Request $request) {
    return $request->user()->load('roles');
});

// --- Category Routes ---
Route::controller(CategoryController::class)->prefix('categories')->group(function () {
    Route::get('/', 'getCategories');
    Route::post('/', 'createCategory');
    Route::get('/{categoryId}', 'getCategory');
    Route::patch('/{categoryId}', 'updateCategory');
    Route::delete('/{categoryId}', 'deleteCategory');
});

// --- Product Routes ---
Route::controller(ProductController::class)->prefix('products')->group(function () {
    Route::get('/', 'getProducts');
    Route::post('/', 'createProduct');
    Route::get('/{productId}', 'getProduct');
    Route::patch('/{productId}', 'updateProduct');
    Route::delete('/{productId}', 'deleteProduct');
});

// Nested Route
Route::get('/categories/{categoryId}/products', [ProductController::class, 'getProductsByCategory']);


Route::post('/authors', [AuthorController::class, 'store']);
Route::post('/articles', [ArticleController::class, 'store']);
Route::post('/audience-users', [AudienceController::class, 'createUser']);
Route::post('/subscribe', [AudienceController::class, 'subscribe']);
Route::post('/comments', [CommentController::class, 'store']);

Route::get('/authors/{name}/articles', [ArticleController::class, 'getByAuthor']);
Route::get('/articles/{name}/audiences', [AudienceController::class, 'getByArticle']);
Route::get('/authors/{name}/audiences', [AuthorController::class, 'getAudiences']);
Route::get('/audiences/{name}/comments', [CommentController::class, 'getByAudienceName']);
Route::get('/comments', [CommentController::class, 'getAllWithTopic']);
