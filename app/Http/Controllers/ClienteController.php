<?php

/**
 *
 * @category  Customers
 * @version   1.0
 * @since     2019-24-04
 * @author    Ernesto <ernesto.munoz@cceo.com.mx>
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Persona;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if ($buscar == '') {
            $personas = Persona::orderBy('id', 'desc')->paginate(3);
        } else {
            $personas = Persona::where($criterio, 'like', '%' . $buscar . '%')->orderBy('id', 'desc')->paginate(3);
        }
        return [
            'pagination' => [
                'total'        => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page'     => $personas->perPage(),
                'last_page'    => $personas->lastPage(),
                'from'         => $personas->firstItem(),
                'to'           => $personas->lastItem(),
            ],
            'personas' => $personas
        ];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $persona = new Persona();
        $persona->fill($request->all());
        $persona->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $persona = Persona::findOrFail($request->id);
        $persona->fill($request->all());
        $persona->save();
    }

    public function selectCliente(Request $request)
    {
       if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $clientes = Persona::where('nombre', 'like', '%' . $filtro . '%')
            ->orWhere('num_documento', 'like', '%' . $filtro . '%')
            ->select('id', 'nombre', 'num_documento')
            ->orderBy('nombre', 'asc')->get();

        return ['clientes' => $clientes];
    }
}
