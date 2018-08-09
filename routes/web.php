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

Route::group(['prefix' => 'sales', 'middleware' => ['auth']], function () {
  Route::get('/', [
  	'as' => 'sales',
  	'uses' => 'SalesController@index'
  ]);

  Route::get('/load-data', [
    'as' => 'sales.load.inventory',
    'uses' => 'SalesController@loadInventory'
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

  Route::post("/update/quantity/{id}", [
		'as' => 'inventory.update.quantity',
		'uses' => 'InventoryController@updateQuantity'
	]);

});
