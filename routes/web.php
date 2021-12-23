<?php

use App\Http\Controllers\PartController;
use App\Http\Controllers\RunController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ChemicalController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return redirect('/run/');
    // return Inertia::render('Welcome', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');
Route::middleware(['auth:sanctum', 'verified'])->delete('/run/deleteRun/{id}', [RunController::class, 'deleteRun'])->name('run.deleteRun');
Route::middleware(['auth:sanctum', 'verified'])->get('/chemical/getChemicals/{type}', [ChemicalController::class, 'getChemicals'])->name('chemical.getChemicals');
Route::middleware(['auth:sanctum', 'verified'])->get('/company/getCompanies/{type}', [CompanyController::class, 'getCompanies'])->name('company.getCompanies');
Route::middleware(['auth:sanctum', 'verified'])->get('/run/download/{id}', [RunController::class, 'downloadPdf'])->name('run.downloadPdf');
Route::middleware(['auth:sanctum', 'verified'])->get('/run/downloadPlus/{id}', [RunController::class, 'downloadPlus'])->name('run.downloadPlus');
Route::middleware(['auth:sanctum', 'verified'])->get('/run/getAllRuns/{status}', [RunController::class, 'getAllRuns'])->name('run.getAllRuns');
Route::middleware(['auth:sanctum', 'verified'])->post('/note/add', [NoteController::class, 'store'])->name('note.add');
Route::middleware(['auth:sanctum', 'verified'])->post('/photo/getAllUrlSignature', [PhotoController::class, 'getAllUrlSignature'])->name('photo.signature');
Route::middleware(['auth:sanctum', 'verified'])->put('/run/closeRun/{id}', [RunController::class, 'closeRun'])->name('run.closeRun');
Route::middleware(['auth:sanctum', 'verified'])->put('/run/reopenRun/{id}', [RunController::class, 'reopenRun'])->name('run.reopenRun');
Route::middleware(['auth:sanctum', 'verified'])->get('/user/getUsers/{type}', [UserController::class, 'getUsers'])->name('user.getUsers');
Route::middleware(['auth:sanctum', 'verified'])->get('/email/runResult', [MailController::class, 'store'])->name('mail.runResult');
Route::middleware(['auth:sanctum', 'verified'])->post('/user/resetPassword', [UserController::class, 'resetPassword'])->name('user.resetPassword');


//Resources
Route::middleware(['auth:sanctum', 'verified'])->resource('chemical', ChemicalController::class);
Route::middleware(['auth:sanctum', 'verified'])->resource('photo', PhotoController::class);
Route::middleware(['auth:sanctum', 'verified'])->resource('company', CompanyController::class);
Route::middleware(['auth:sanctum', 'verified'])->resource('part', PartController::class);
Route::middleware(['auth:sanctum', 'verified'])->resource('run', RunController::class);
Route::middleware(['auth:sanctum', 'verified'])->resource('report', ReportController::class);
Route::middleware(['auth:sanctum', 'verified'])->resource('user', UserController::class);


