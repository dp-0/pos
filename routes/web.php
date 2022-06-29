<?php

use App\Http\Livewire\Admin\Category\CategoryController;
use App\Http\Livewire\Admin\Customer\CustomerController;
use App\Http\Livewire\Admin\Product\ProductController;
use App\Http\Livewire\Admin\Sales\SalesController;
use App\Http\Livewire\Admin\Supplier\SupplierController;
use App\Http\Livewire\Admin\Trialbalance\TrialBalanceController;
use App\Http\Livewire\Admin\User\UserController;
use App\Http\Livewire\User\Sales\NewEntryController;
use App\Http\Livewire\Admin\Dashboard\DashboardController;
use App\Http\Livewire\User\Bill\BillController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified','admin'])->prefix('admin')->group(function () {
    //route for dashboard
    Route::get('/dashboard',DashboardController::class)->name('dashboard');
    //route for user
    Route::get('/user',UserController::class)->name('user');


    //route for supplier
    Route::get('/supplier',SupplierController::class)->name('supplier');

    //route for customer
    Route::get('/customer',CustomerController::class)->name('customer');

    //route for category
    Route::get('/category', CategoryController::class)->name('category');

    //route for product
    Route::get('/product', ProductController::class)->name('product');

    //route for sales
    Route::get('/sales',SalesController::class)->name('sales');
    

    //route for trial balance
    Route::get('/balance',TrialBalanceController::class)->name('balance');

});


Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified'])->prefix('user')->group(function () {
    //route for new entry
    Route::get('/sales/entry',NewEntryController::class)->name('sales.entry');

    //route for bill generate
    Route::get('/sales/bill',BillController::class)->name('user.bill');
});

Route::get('/redirectUser',function(){
    if(Auth::user()->utype == 'admin'){
        return redirect()->route('dashboard');
    }
    else{
        return redirect()->route('sales.entry');
    }
});