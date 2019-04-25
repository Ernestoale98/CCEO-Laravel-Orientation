<?php
use App\Categoria;
//use Symfony\Component\Routing\Route;


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
    return view('contenido/contenido');
});

/*
Metodos para categorias
*/
Route::get('/categoria','CategoriaController@index');
Route::post('/categoria/registrar','CategoriaController@store');
Route::put('/categoria/actualizar','CategoriaController@update');
Route::put('/categoria/activar_desactivar','CategoriaController@activar_desactivar');
Route::get('/categoria/selectCategoria','CategoriaController@selectCategoria');

/*
Metodos para articulos
*/
Route::get('/articulo','ArticuloController@index');
Route::post('/articulo/registrar','ArticuloController@store');
Route::put('/articulo/actualizar','ArticuloController@update');
Route::put('/articulo/desactivar','ArticuloController@desactivar');
Route::put('/articulo/activar','ArticuloController@activar');
