<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $response = Http::post('http://localhost:8000/graphql', [
        'query' => '
            query {
                posts {
                    data {
                        id
                        title
                        body
                        created_at
                        updated_at
                        }
                    }
                }
        '
    ]);
    //dd($response->json());
    return Inertia::render('Welcome', [
        'Rposts' => $response->json()['data']['posts']['data']
    ]);
});

Route::get('/create', function () {
    $response = Http::post('http://localhost:8000/graphql', [
        'query' => '
            mutation {
                createPostResolver(user_id: 1, title: "Hello from Laravel", body: "Laravel") {
                    id
                    title
                }
            }
        '
    ]);

    return $response->json();
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
