<?php

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

Route::get('login', [
	'as' => 'login',
	'uses' => 'LoginController@login'
]);

Route::get('logout', [
	'as' => 'logout',
	'uses' => 'LoginController@logout'
])->middleware('auth');

Route::post('authenticate', [
	'as' => 'authenticate',
	'uses' => 'LoginController@authenticate'
]);

Route::group(['middleware' => ['auth', 'role:admin|owner']], function () {
  Route::get('dashboard', [
  	'as' => 'dashboard',
  	'uses' => 'DashboardController@index'
  ])->middleware('auth');
});

Route::get('my-profile', [
  'as' => 'profile',
  'uses' => 'UserController@profile'
]);

Route::post('update-profile', [
  'as' => 'update-profile',
  'uses' => 'UserController@updateProfile'
]);

Route::group(['prefix' => 'users', 'middleware' => ['auth']], function () {

});

Route::group(['prefix' => 'sales', 'middleware' => ['auth']], function () {
  Route::get('/', [
  	'as' => 'sales',
  	'uses' => 'SalesController@index'
  ]);

  Route::get('/load-data', [
    'as' => 'sales.load.inventory',
    'uses' => 'SalesController@loadInventory'
  ]);

  Route::get('/load-data/senior', [
    'as' => 'sales.load.senior',
    'uses' => 'SalesController@loadSeniorCitizen'
  ]);

  Route::get('/load-cart', [
    'as' => 'sales.load.cart',
    'uses' => 'SalesController@loadCart'
  ]);

  Route::post('/add-to-cart', [
    'as' => 'add-to-cart',
    'uses' => 'SalesController@addToCart'
  ]);

  Route::post('/update-cart', [
    'as' => 'update-cart',
    'uses' => 'SalesController@updateCart'
  ]);

  Route::post('/remove-to-cart', [
    'as' => 'remove-to-cart',
    'uses' => 'SalesController@removeCart'
  ]);

  Route::post('/update-order', [
    'as' => 'update-order',
    'uses' => 'SalesController@updateOrder'
  ]);

  Route::post('/finalize', [
    'as' => 'finalize',
    'uses' => 'SalesController@finalizeOrder'
  ]);
});

Route::group(['prefix' => 'reports', 'middleware' => ['auth']], function () {
  Route::get("/", [
    'as' => 'reports',
    'uses' => 'DashboardController@reportsIndex'
  ]);

  Route::get('today/download', [
    'as' => 'reports.today',
    'uses' => 'DashboardController@todayDownload'
  ]);

  Route::get('weekly/download', [
    'as' => 'reports.weekly',
    'uses' => 'DashboardController@weeklyDownload'
  ]);

  Route::get('monthly/download', [
    'as' => 'reports.monthly',
    'uses' => 'DashboardController@monthlyDownload'
  ]);

  Route::get('ending/inventory/download', [
    'as' => 'reports.ending.inventory',
    'uses' => 'DashboardController@endingInventory'
  ]);

  Route::get('generate-date-range', [
    'as' => 'report-generate-date-range',
    'uses' => 'DashboardController@generateDateRange'
  ]);

  Route::get('download-date-range', [
    'as' => 'report-download-date-range',
    'uses' => 'DashboardController@downloadDateRange'
  ]);

  Route::get('import/inventory', [
    'as' => 'import-inventory',
    'uses' => 'DashboardController@importInventory'
  ]);
});

Route::group(['prefix' => 'inventory', 'middleware' => ['auth', 'role:admin|owner']], function () {

  Route::get("/", [
		'as' => 'inventory.index',
		'uses' => 'InventoryController@index'
	]);

  Route::get("/new", [
		'as' => 'inventory.new',
		'uses' => 'InventoryController@create'
	]);

  Route::get("/load-data", [
		'as' => 'inventory.load.data',
		'uses' => 'InventoryController@loadData'
	]);

  Route::get("/view/{id}", [
		'as' => 'inventory.view',
		'uses' => 'InventoryController@view'
	]);

  Route::get("/view/trail/{id}", [
		'as' => 'inventory.trail',
		'uses' => 'InventoryController@loadTrail'
	]);

  Route::post("/save", [
		'as' => 'inventory.save',
		'uses' => 'InventoryController@save'
	]);

  Route::post("/update/{id}", [
		'as' => 'inventory.update',
		'uses' => 'InventoryController@update'
	]);

  Route::post("/archive/{id}", [
		'as' => 'inventory.archive',
		'uses' => 'InventoryController@archiveInventory'
	]);

  Route::post("/update/quantity/{id}", [
		'as' => 'inventory.update.quantity',
		'uses' => 'InventoryController@updateQuantity'
	]);

});
