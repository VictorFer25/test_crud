<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    //
    public function create(){
        return view('modulos.producto');
    }

    public function save(Request $request){
        try{
            $data = [ 'data' => $request->all() ];

            $validator = Validator::make($data, [
                'data.*.categoria' => 'required|string',
                'data.*.clave' => 'required|string',
                'data.*.precio' => 'required|numeric',
                'data.*.producto' => 'required|string',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                foreach ($errors->all() as $message) {
                    throw new Exception($message,900);
                }
            }
            DB::beginTransaction();
            foreach($request->all() as $item){
                Producto::create([
                    'clave'=>$item['clave'],
                    'categoria'=>$item['categoria'],
                    'producto'=>$item['producto'],
                    'precio'=>$item['precio']
                ]);
            }

            DB::commit();

            return response()->json(['status'=>true]);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'status'=>false,
                'msg'=> ($e->getCode() == 900) ? $e->getMessage() : "Lo sentimos, ocurrio un error interno"
            ]);
        }

    }

    public function show(){
        return view('modulos.lista_producto');
    }

    public function list(){
        try{
            extract(request()->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn', 'sort','direction']));
            $params = json_decode($query);

            $productos = DB::table('productos')->select('*');

            if(isset($params->clave)){
                $productos->where("clave", 'LIKE', "%{$params->clave}%");
            }

            if(isset($params->categoria)){
                $productos->where("categoria", 'LIKE', "%{$params->categoria}%");
            }

            if(isset($params->producto)){
                $productos->where("producto", 'LIKE', "%{$params->producto}%");
            }


            $result['count'] = $productos->count();
            $result["data"]  = $productos->orderBy($sort,$direction)->take($limit)->skip($limit * ($page - 1) )->get();

        }catch(Exception $e){

            $result['count'] = 0;
            $result["data"] = array();
            $result["error"] = true;
        }

        return $result;
    }

    public function destroy($id){
        try{
            $producto = Producto::find($id);
            if(is_null($producto)){
                throw new Exception('No se encontro el producto seleccionado',900);
            }
            $producto->delete();

            return response()->json(['status'=>true]);

        }catch(Exception $e){
            return response()->json([
                'status'=>false,
                'msg'=> ($e->getCode() == 900) ? $e->getMessage() : "Lo sentimos, ocurrio un error interno"
            ]);
        }
    }
}
