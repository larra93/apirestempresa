<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarServicio;
use Illuminate\Http\Request;
use App\Http\Requests\GuardarServicio;
use App\Models\Service;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;


class ServicesController extends Controller
{

    public function index()
    {
        return Service::all();
    }
    
    public function store(GuardarServicio $request)
    {
        try{
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

    }catch(\Exception $e){

        return response()->json(['res' => false,'msg' => $e->getMessage()],500); 
    }
    }

    public function update(ActualizarServicio $request, $id)
    {
    
        try{
            $servicio = Service::find($id);
            $imagenPrevia = $servicio->imagen;
            if ($request->hasFile('imagen')){
                $imagen = $request->file('imagen');
                $nombreFoto = time().'.'.$imagen->getClientOriginalExtension();
                $destino = public_path('images/servicios');
                $request->imagen->move($destino, $nombreFoto);
                $red = Image::make($destino.'/'.$nombreFoto);
                $red->resize(200,null, function($constraint){
                $constraint->aspectRatio();
            });
            $red->save($destino.'/thumbs/'.$nombreFoto);
                unlink($destino.'/'.$imagenPrevia);
                unlink($destino.'/thumbs/'.$imagenPrevia);
                $servicio->imagen=$nombreFoto; 
    
              }
    
                $servicio->nombre= $request->nombre;
                $servicio->descripcion= $request->descripcion;
                $servicio->update();

                return response()->json([
                    'res' => true,
                    'msg' => 'Servicio actualizado correctamente'
                ],200);
    
            }catch(\Exception $e){

                return response()->json(['res' => false,'msg' => $e->getMessage()],500); 
            }
                
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
