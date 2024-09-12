<?php

use App\Livewire\MwcnuResource\FormResponseJemaah;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('form/{code}', FormResponseJemaah::class)->name("form-response");

// Route::get("/dashboard/profile", EditProfile::class)->name("profile");

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});


Route::get('/surat_tugas/{filename}', function ($filename) {
    header("Content-type: application/pdf");

    $filename = "../public/storage/surat_tugas/" . $filename;

    $response = Response::make(readfile($filename), 200);
    $response->header('Content-Type', 'application/pdf');
    return $response;
})->name("surat-tugas");

require __DIR__ . '/auth.php';
