<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\AgeLevelController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\CoachPlanController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\FAQsController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LessonProgramController;
use App\Http\Controllers\OurLocationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramDetailsController;
use App\Http\Controllers\ProgramsController;
use App\Http\Controllers\VenuesController;
use App\Http\Controllers\VenueDetailsController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/cacheclear', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('route:cache');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    // dd("Done");
    return response()->json(["message" => "Cache clear", "status" => true]);
});

Route::get('/', function () {
    return redirect()->route('login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
Route::get('changepassword', [HomeController::class, 'ChangePassword'])->name('change-password');
Route::post('updatepassword', [HomeController::class, 'UpdatePassword'])->name('updatepassword');
Route::post('/verify-current-password', [HomeController::class, 'verifyCurrentPassword'])->name('verifyCurrentPassword');

Route::resource('about-us', AboutUsController::class);
Route::resource('blogs', BlogController::class);
Route::resource('galleries', GalleryController::class);
Route::resource('venues', VenuesController::class);
Route::resource('venue-details', VenueDetailsController::class);
Route::resource('coach-plan', CoachPlanController::class);
Route::resource('coaches', CoachController::class);
Route::resource('location', OurLocationController::class);
Route::resource('programs', ProgramsController::class);
Route::resource('program-details', ProgramDetailsController::class);
Route::resource('age-level', AgeLevelController::class);
Route::resource('lesson-program', LessonProgramController::class);
Route::resource('faqs', FAQsController::class);
Route::resource('contact-us', ContactUsController::class);
Route::resource('banners', BannerController::class);
Route::post('/gallery/delete-image', [GalleryController::class, 'deleteImage'])->name('gallery.deleteImage');


require __DIR__.'/auth.php';
