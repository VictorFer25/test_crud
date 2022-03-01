<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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

    public function download_excel(){
        $spreadsheet = new Spreadsheet();
        date_default_timezone_set('America/Cancun');
        setlocale(LC_ALL, "es_ES");

        /* ESTILOS */
        $header = [
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => array('rgb' => 'FFFFFF'),
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => '264065',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                ],
            ],
        ];

        $encabezados_tabla  = [
            'font' => [
                'bold' => true,
                'size' => 14
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => '8db3e2',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                ],
            ],
        ];
        $celdas_texto  = [
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                ],
            ],
        ];




        $spreadsheet->getSheet(0); //inicio el la primera hora
        $spreadsheet->getSheetByName("LISTA DE PRODUCTOS"); // nombre de la hoja
        $sheet = $spreadsheet->setActiveSheetIndex(0); // hoja principal
        $sheet->setTitle("LISTA DE PRODUCTOS");

        $sheet->mergeCells("A2:D4");


        $sheet->setCellValue("A2", "LISTA DE PRODUCTOS");
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getStyle('A2:D4')->applyFromArray($header);

        $cell = 'A';

        $headings = [
            'CLAVE',
            'CATEGORIA',
            'PRODUCTO',
            'PRECIO'
        ];
        foreach ($headings as $head) {
            //ENCABEZADOS DE LA TABLA

            $sheet->setCellValue("{$cell}7", $head);
            $sheet->getStyle("{$cell}7")->applyFromArray($encabezados_tabla);
            $sheet->getColumnDimension($cell)->setAutoSize(true);
            $cell++;
        }

        $datos = Producto::orderBy('clave','asc')->get();
        $row = 8;
        $celda = 'A';
        foreach ($datos as $key => $item) {

            $sheet->setCellValue($celda . $row, $item->clave);
            $sheet->getStyle($celda . $row)->applyFromArray($celdas_texto);
            $sheet->getColumnDimension($celda)->setAutoSize(true);
            $celda++;

            $sheet->setCellValue($celda . $row, $item->categoria);
            $sheet->getStyle($celda . $row)->applyFromArray($celdas_texto);
            $sheet->getColumnDimension($celda)->setAutoSize(true);
            $celda++;


            $sheet->setCellValue($celda . $row, $item->producto);
            $sheet->getStyle($celda . $row)->applyFromArray($celdas_texto);
            $sheet->getColumnDimension($celda)->setAutoSize(true);
            $celda++;

            $sheet->setCellValue($celda . $row, $item->precio);
            $sheet->getStyle($celda . $row)->applyFromArray($celdas_texto);
            $sheet->getColumnDimension($celda)->setAutoSize(true);
            $celda++;

            $row++;
            $celda = 'A';
        }

          /***************************************** */
        // SALIDA DEL DOCUMENTO AL USUARIO
        $writer = new Xlsx($spreadsheet);
        $filename = "LISTA_DE_PRODUCTOS_" . date("Y-m-d") . "_" . date("H:i:s") . ".xlsx";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename);
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        //=======================================
    }
}
