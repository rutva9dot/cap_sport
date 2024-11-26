<?php

use App\Http\Controllers\LandingPageApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('about-us-list', [LandingPageApiController::class, 'AboutUsList']);
Route::post('blog-list', [LandingPageApiController::class, 'BlogList']);
Route::post('gallery-list', [LandingPageApiController::class, 'GalleryList']);
Route::post('venue-details-list', [LandingPageApiController::class, 'VenueDetailsList']);
Route::post('venue-list', [LandingPageApiController::class, 'VenueList']);
Route::post('plan-list', [LandingPageApiController::class, 'PlanList']);
Route::post('coach-list', [LandingPageApiController::class, 'CoachList']);
Route::post('our-location', [LandingPageApiController::class, 'OurLocation']);
Route::post('faqs-list', [LandingPageApiController::class, 'FAQsList']);

Route::post('banner-list', [LandingPageApiController::class, 'BannerList']);

Route::post('lesson-program', [LandingPageApiController::class, 'LessonProgramList']);
Route::post('age-level-list', [LandingPageApiController::class, 'AgeLevelList']);

Route::post('program-details-list', [LandingPageApiController::class, 'ProgramDetailsList']);
Route::post('program-list', [LandingPageApiController::class, 'ProgramList']);

Route::post('add-contact-us', [LandingPageApiController::class, 'AddContactUs']);
