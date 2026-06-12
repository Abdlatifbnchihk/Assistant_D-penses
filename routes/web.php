<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceiptController;
use App\Jobs\ExtraireDepensesDuRecu;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/test-queue', function(){
    ExtraireDepensesDuRecu::dispatch();

    return 'Job dispatched!';
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Receipt
    Route::get('/receipt', [ReceiptController::class, 'index'])->name('Receipt.index');
    Route::get('/receipt/create', [ReceiptController::class, 'create'])->name('Receipt.create');
    Route::post('/receipt', [ReceiptController::class, 'store'])->name('Receipt.store');
    Route::get('/receipt/{recu}', [ReceiptController::class, 'show'])->name('Receipt.show');
    Route::delete('/recus/{recu}',  [ReceiptController::class, 'destroy'])->name('Receipt.destroy');

});

require __DIR__.'/auth.php';
