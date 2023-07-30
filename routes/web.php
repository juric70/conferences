<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\ConferenceDayController;
use App\Http\Controllers\ConferenceRoleController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationsOfferController;
use App\Http\Controllers\OrganizationTypeController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PartnerTypeController;
use App\Http\Controllers\RoleController;

use App\Http\Controllers\TimetableController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::controller(RoleController::class)->group(function (){
    Route::get('/roles', 'index');
    Route::post('/roles', 'store');
    Route::get('roles/{id}', 'show');
    Route::put('roles/{id}', 'update');
    Route::delete('roles/{id}', 'destroy');

});

Route::controller(CountryController::class)->group(function (){
    Route::get('/countries', 'index');
    Route::post('/countries', 'store');
    Route::get('/countries/{id}', 'show');
    Route::put('/countries/{id}', 'update');
    Route::delete('/countries/{id}', 'destroy');
});

Route::controller(CityController::class)->group(function (){
    Route::get('/cities', 'index');
    Route::post('/cities', 'store');
    Route::get('/cities/{id}', 'show');
    Route::put('/cities/{id}', 'update');
    Route::delete('/cities/{id}', 'destroy');
});

Route::controller(ConferenceController::class)->group(function (){
    Route::get('/conferences', 'index');
    Route::post('/conferences', 'store');
    Route::post('/conferences/{id}/partners', 'storePartners');
    Route::get('/conferences/{id}', 'show'); //Vraća konferenciju bez njezinih partnera
    Route::get('/conferences/{id}/partners', 'showConferencePartners'); //Vraća konferencije i njezine partnere
    Route::put('/conferences/{id}', 'update');
    Route::delete('/conferences/{id}', 'destroy');
});

Route::controller(CategoryController::class)->group(function (){
    Route::get('/categories', 'index');
    Route::post('/categories', 'store');
    Route::get('/categories/{id}', 'show');
    Route::put('/categories/{id}', 'update');
    Route::delete('/categories/{id}', 'destroy');
});

Route::controller(ConferenceDayController::class)->group(function (){
    Route::get('/conferenceDay', 'index');
    Route::post('/conferenceDay', 'store');
    Route::post('/conferenceDay/{id}/categories', 'storeCategory');
    Route::get('/conferenceDay/{id}', 'show');
    Route::put('/conferenceDay/{id}', 'update');
    Route::delete('/conferenceDay/{id}', 'destroy');
    Route::delete('/conferenceDay/{id}/categories', 'destroyCategory');
});

Route::controller(ConferenceRoleController::class)->group(function (){
    Route::get('/conferenceRoles', 'index');
    Route::post('/conferenceRoles', 'store');
    Route::get('/conferenceRoles/{id}', 'show');
    Route::put('/conferenceRoles/{id}', 'update');
    Route::delete('/conferenceRoles/{id}', 'destroy');
});

Route::controller(OrganizationTypeController::class)->group(function (){
    Route::get('/organizationTypes', 'index');
    Route::post('/organizationTypes', 'store');
    Route::get('/organizationTypes/{id}', 'show');
    Route::put('/organizationTypes/{id}', 'update');
    Route::delete('/organizationTypes/{id}', 'destroy');
});

Route::controller(OrganizationController::class)->group(function (){
    Route::get('/organizations', 'index');
    Route::post('/organizations', 'store');
    Route::get('/organizations/{id}', 'show');
    Route::put('/organizations/{id}', 'update');
    Route::put('/organizations/{id}/approval', 'update');
    Route::delete('/organizations/{id}', 'destroy');

    Route::post('/subscriptions/{organization_id}', 'storeOrganizationSubscription');
    Route::put('/subscriptions/{id}', 'updatePaymentStatus');
    Route::get('/organizations/{id}/all', 'showAllSubscriptionsOfOrganization');
    Route::get('/organizations/{id}/paid', 'showAllPaidSubscriptionsOfOrganization');
    Route::get('/organizations/{id}/unpaid', 'showAllUnpaidSubscriptionsOfOrganization');
    Route::get('/subscriptions/{id}', 'showAllOrganisationsOfSubscription');
    Route::delete('/subscriptions/{id}', 'destroySubscription');
});

Route::controller(PartnerTypeController::class)->group(function (){
    Route::get('/partnerTypes', 'index');
    Route::post('/partnerTypes', 'store');
    Route::get('/partnerTypes/{id}', 'show');
    Route::put('/partnerTypes/{id}', 'update');
    Route::delete('/partnerTypes/{id}', 'destroy');
});

Route::controller(TimetableController::class)->group(function (){
    Route::get('/timetables', 'index');
    Route::post('/timetables', 'store');
    Route::get('/timetables/{id}', 'show');
    Route::put('/timetables/{id}', 'update');
    Route::delete('/timetables/{id}', 'destroy');
});

Route::controller(OrganizationsOfferController::class)->group(function (){
    Route::get('/organizationsOffers', 'index');
    Route::post('/organizationsOffers', 'store');
    Route::get('/organizationsOffers/{id}', 'show');
    Route::put('/organizationsOffers/{id}', 'update');
    Route::delete('/organizationsOffers/{id}', 'destroy');
});
Route::controller(PartnerController::class)->group(function (){
    Route::get('/conference/{conference_id}/partners/{type_id}', 'showPartnersWithSameType'); //prikazuje partnere konferencije koji imaju isti tip partnerstva
    Route::get('/organization/{conference_id}/partners', 'conferencesByOrganization');
    Route::put('/partners/{id}', 'update');
    Route::delete('/partners/{id}', 'destroy');
});
