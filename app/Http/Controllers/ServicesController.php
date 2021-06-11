<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarServicio;
use App\Http\Requests\GuardarServicio;
use App\Models\Service;
use Image;


class ServicesController extends Controller
{

    public function index()
    {
        return Service::all();
    }
    
    public function store(GuardarServicio $request)
    {
        //Service::create($request->all());
        
        $imagen = $request->file('imagen');
            $nombreImagen = time().'.'.$imagen->getClientOriginalExtension();
            $destino = public_path('images/servicios');
            $request->imagen->move($destino, $nombreImagen);
            $red = Image::make($destino.'/'.$nombreImagen);
            $red->resize(200,null, function($constraint){
                $constraint->aspectRatio();
            });
            $red->save($destino.'/thumbs/'.$nombreImagen);
            
            $servicio = Service::create([
                'nombre'=>$request->nombre,
                'descripcion'=>$request->descripcion,
                'imagen'=>$nombreImagen
            ]); 
        
        
        return response()->json([
            'res' => true,
            'msg' => 'Servicio Guardado Correctamente'
        ],200);
    }

    public function update(ActualizarServicio $request, Service  $servicio)
    {
        $servicio->update($request->all());
        return response()->json([
            'res' => true,
            'msg' => 'Servicio actualizado correctamente'
        ],200);
    }

    public function show($id)
    {
        $servicio = Service::findOrFail($id);
        return response()->json([
            'res' => true,
            'Servicio' => $servicio
        ],200);
    }
}
