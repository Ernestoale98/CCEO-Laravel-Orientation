<?php

/**
 *
 * @category  Suppliers
 * @version   1.0
 * @since     2019-24-04
 * @author    Ernesto <ernesto.munoz@cceo.com.mx>
 */


namespace App\Http\Controllers;

use Illuminate\Http\Request;
//Clase para usar transacciones
use Illuminate\Support\Facades\DB;

use App\Proveedor;
use App\Persona;


class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '') {
            $personas = Proveedor::join('personas', 'proveedores.id', '=', 'personas.id')
                ->select(
                    'personas.id',
                    'personas.nombre',
                    'personas.tipo_documento',
                    'personas.num_documento',
                    'personas.direccion',
                    'personas.telefono',
                    'personas.email',
                    'proveedores.contacto',
                    'proveedores.telefono_contacto'
                )
                ->orderBy('personas.id', 'desc')->paginate(3);
        } else {
            $personas = Proveedor::join('personas', 'proveedores.id', '=', 'personas.id')
                ->select(
                    'personas.id',
                    'personas.nombre',
                    'personas.tipo_documento',
                    'personas.num_documento',
                    'personas.direccion',
                    'personas.telefono',
                    'personas.email',
                    'proveedores.contacto',
                    'proveedores.telefono_contacto'
                )
                ->where('personas.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('personas.id', 'desc')->paginate(3);
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

        try {
            DB::beginTransaction();
            $persona = new Persona();
            $persona->fill($request->all());
            $persona->save();

            $proveedor = new Proveedor();
            $proveedor->fill($request->all());
            $proveedor->id = $persona->id;
            $proveedor->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try {
            DB::beginTransaction();
            //Buscar primero el proveedor a modificar
            $proveedor = Proveedor::findOrFail($request->id);
            //Despues accessar a esa persona
            $persona = Persona::findOrFail($proveedor->id);

            $persona->fill($request->all());
            $persona->save();

            $proveedor->fill($request->all());
            $proveedor->id = $persona->id;
            $proveedor->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
