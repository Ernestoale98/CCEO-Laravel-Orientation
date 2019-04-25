<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//Importar Paginating Query
//use Illuminate\Support\Facades\DB;
//Importar el modelo
use App\Categoria;
use SebastianBergmann\Environment\Console;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $categorias = Categoria::orderBy('id', 'desc')->paginate(3);
        } else {
            $categorias = Categoria::where($criterio,'like','%'. $buscar . '%')->paginate(3);
         }

        //Esta si funciona
        //$categorias = Categoria::paginate(3);

        return [
            'pagination' => [
                'total'             => $categorias->total(),
                'current_page'      => $categorias->currentPage(),
                'per_page'          => $categorias->perPage(),
                'last_page'         => $categorias->lastPage(),
                'from'              => $categorias->firstItem(),
                'to'                => $categorias->lastItem(),
            ],
            'categorias' => $categorias
        ];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categoria = new Categoria();
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->condicion = '1';
        $categoria->save();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categoria = Categoria::findOrFail($request->id);
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->condicion = '1';
        $categoria->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categoria = Categoria::findOrFail($request->id);
        $categoria->condicion = '0';
        $categoria->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categoria = Categoria::findOrFail($request->id);
        $categoria->condicion = '1';
        $categoria->save();
    }
}
