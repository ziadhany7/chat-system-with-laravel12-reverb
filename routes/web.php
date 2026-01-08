<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware(['auth', 'verified'])->group(function () {
    // تم دمج الداشبورد هنا ليعمل من خلال الكنترولر فقط
    Route::get('/', [ChatController::class, 'index'])->name('dashboard');

    Route::get('/chat/{user}', [ChatController::class, 'chatWith']);
    Route::post('/messages', [ChatController::class, 'send']);
    Route::get('/broadcast/messages', [ChatController::class, 'getBroadcastMessages']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
