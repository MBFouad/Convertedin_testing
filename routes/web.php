<?php
//
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DevicesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DeviceMapController;
use App\Http\Controllers\DistributionsController;
use App\Http\Controllers\SubDistributionController;
use App\Http\Controllers\DeviceTypeCategoryController;
use App\Http\Controllers\DeviceFileController;
use App\Http\Controllers\ServiceManualController;
use App\Http\Controllers\MailLogController;
use App\Http\Controllers\MaintenanceKeysController;
use App\Http\Controllers\TechDataController;
use App\Http\Controllers\SparePartsController;
use App\Http\Controllers\TechSupport\DeviceController as TechSupportDeviceController;
use App\Http\Controllers\TechSupport\CustomerController as TechSupportCustomerController;
use App\Http\Controllers\TechSupport\KeysController as TechSupportKeysController;
use Illuminate\Support\Facades\Route;
use \Illuminate\Support\Str;
use \App\Http\Controllers\DeviceTypeController;
use \App\Http\Controllers\SerialController;
use \App\Http\Controllers\FilesController;
use App\Http\Controllers\AdminPasswordController;
use App\Http\Controllers\TechSupport;
use App\Http\Controllers\AdminsController;
use App\Http\Controllers\MailConfigController;
use App\Http\Controllers\FileTypesController;
use App\Http\Controllers\DeviceDistributionController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\LangFileController;
use App\Http\Controllers\LanguageManagerController;
use App\Http\Controllers\RequestsController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\CustomerSerialsController;
use App\Http\Controllers\CustomerRequestsController;
use App\Http\Controllers\KeysController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\Customer\BusDataController as CustomerBusDataController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AccountActivateController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

use App\Utilities\Constants;

Route::group(['middleware' => 'auth'], static function () {
        Route::get('/', function () {
            $registration_token = Str::random(32);
            return view('auth/checkSerialNumber', compact('registration_token'));
        });
        Route::get('/logout', [AuthenticatedSessionController::class, 'destroy']);
        Route::get('/home', [HomeController::class, 'tasks'])->name('home');
        Route::get('/statistics', [HomeController::class, 'statistics'])->name('statistics');
        Route::get('/task/create', [HomeController::class, 'create'])->name('task.create');
        Route::POST('/task/store', [HomeController::class, 'store'])->name('task.store');

    });

require __DIR__ . '/auth.php';

