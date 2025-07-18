<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContactApiController;
use App\Models\Contact;

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

//Contact Route

Route::get('contact/get-master-contacts', function (Request $request) {
    return response()->json(
        Contact::whereDoesntHave('mergedContact')
        ->where([
            ['id','<>',$request->id],
            ['is_master',1]
        ])->select('id', 'name','email')->get());
})->name('api.contact.get-master-contacts');

// Custom route for merging contacts
Route::post('contact/merge-contact', [ContactApiController::class, 'merge_contact'])
    ->name('api.contact.merge_contact');

Route::apiResource('contact',ContactApiController::class)->only([
    'store','update','destroy',
])->names([
    'store' => 'api.contact.store',
    'update' => 'api.contact.update',
    'destroy' => 'api.contact.destroy',
]);
//Contact Route End