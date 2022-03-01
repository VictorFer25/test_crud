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

// Route::get('/', function () {

//     return view('welcome');
// });

use App\Http\Controllers\ProductoController;

Route::get('/',[ProductoController::class,'create'])->name('producto.create');
Route::get('/lista-productos',[ProductoController::class,'show'])->name('producto.show');


Route::post('/productos/save',[ProductoController::class,'save'])->name('producto.save');
Route::get('/productos/lista',[ProductoController::class,'list'])->name('producto.list');
Route::delete('/productos/{id}/destroy',[ProductoController::class,'destroy'])->name('producto.destroy');
Route::post('/productos/download-excel',[ProductoController::class,'download_excel'])->name('producto.download_excel');
