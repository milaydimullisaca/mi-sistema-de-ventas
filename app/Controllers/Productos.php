<?php
namespace App\Controllers;

use App\Controllers\BaseControllers;
use App\Models\ProductosModel;
use App\Models\UnidadesModel;
use App\Models\CategoriasModel;
use App\Libraries\Barcode;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Productos extends BaseController
{
    protected $productos;
    protected $unidades;
    protected $categorias;

    public function __construct()
    {
        $this->productos = new ProductosModel();
        $this->unidades = new UnidadesModel();
        $this->categorias = new CategoriasModel();
        helper(["form", 'upload']);
    }

    public function index($activo = 1)
    {
        $productos = $this->productos->where('activo', $activo)->findAll();

        $data = [
            'titulo' => 'Productos',
            'datos' => $productos
        ];

        echo view('cabecera');
        echo view('productos/productos', $data);
        echo view('pie');
    }

    public function eliminados($activo = 0)
    {
        $productos = $this->productos->where('activo', $activo)->findAll();

        $data = [
            'titulo' => 'Productos eliminados',
            'datos' => $productos
        ];

        echo view('cabecera');
        echo view('productos/eliminados', $data);
        echo view('pie');
    }

    public function nuevo()
    {
        $unidades = $this->unidades->where('activo', 1)->findAll();
        $categorias = $this->categorias->where('activo', 1)->findAll();

        $data = [
            'titulo' => 'Agregar producto',
            'unidades' => $unidades,
            'categorias' => $categorias
        ];

        echo view('cabecera');
        echo view('productos/nuevo', $data);
        echo view('pie');
    }

    public function insertar()
    {
        $validation = \Config\Services::validation();

        $reglas = [
            'codigo' => [
                'rules' => 'required|is_unique[productos.codigo]',
                'errors' => [
                    'required' => 'El código es obligatorio.',
                    'is_unique' => 'El código ingresado ya existe.'
                ]
            ],
            'nombre' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El nombre es obligatorio.'
                ]
            ]
        ];


        if ($this->request->getMethod() === 'POST') {
            $this->productos->save([

                "codigo" => $this->request->getPost('codigo'),
                'nombre' => $this->request->getPost('nombre'),
                'precio_venta' => $this->request->getPost('precio_venta'),
                'precio_compra' => $this->request->getPost('precio_compra'),
                'stock_minimo' => $this->request->getPost('stock_minimo'),
                'inventariable' => $this->request->getPost('inventariable'),
                'id_unidad' => $this->request->getPost('id_unidad'),
                'id_categoria' => $this->request->getPost('id_categoria')
            ]);

            $id = $this->productos->insertID();

            $validation = $this->validate([
                'img_producto' => [
                    'uploaded[img_producto]',
                    'mime_in[img_producto,image/jpg,image/jpeg,image/png]',
                    'max_size[img_producto,4096]'
                ]
            ]);

            if ($validation) {

                $ruta_logo = './images/productos/' . $id . ".jpg";

                if (file_exists($ruta_logo)) {
                    unlink($ruta_logo);
                }

                $img = $this->request->getFile('img_producto');
                $img->move('./images/productos', $id . '.jpg');

            } else {
                exit('ERROR en la validación');
            }

            return redirect()->to(base_url() . "/productos");


        } else {
            $data = ['titulo' => 'Agregar producto', "validation" => $this->validator];
            echo view('cabecera');
            echo view('productos/nuevo', $data);
            echo view('pie');
        }
    }
    public function editar($id)
    {
        $unidades = $this->unidades->where('activo', 1)->findAll();
        $categorias = $this->categorias->where('activo', 1)->findAll();
        $productos = $this->productos->where('id', $id)->first();
        $data = [
            'titulo' => 'Editar producto',
            'unidades' => $unidades,
            'categorias' => $categorias,
            'productos' => $productos
        ];


        echo view('cabecera');
        echo view('productos/editar', $data);
        echo view('pie');
    }

    public function actualizar()
    {
        $id = $this->request->getPost('id');

        $reglas = [
            'codigo' => [
                'rules' => "required|is_unique[productos.codigo,id,{$id}]",
                'errors' => [
                    'required' => 'El código es obligatorio.',
                    'is_unique' => 'El código ingresado ya existe.'
                ]
            ],
            'nombre' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El nombre es obligatorio.'
                ]
            ]
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()
                ->withInput()
                ->with('errores', $this->validator->getErrors());
        }


        $this->productos->update($this->request->getPost('id'), [

            "codigo" => $this->request->getPost('codigo'),
            'nombre' => $this->request->getPost('nombre'),
            'precio_venta' => $this->request->getPost('precio_venta'),
            'precio_compra' => $this->request->getPost('precio_compra'),
            'stock_minimo' => $this->request->getPost('stock_minimo'),
            'inventariable' => $this->request->getPost('inventariable'),
            'id_unidad' => $this->request->getPost('id_unidad'),
            'id_categoria' => $this->request->getPost('id_categoria')
        ]);
        $id = $this->request->getPost('id');
        $img = $this->request->getFile('img_producto');

        if ($img && $img->isValid() && !$img->hasMoved()) {
            
            $validacionImg = $this->validate([
                'img_producto' => [
                    'mime_in[img_producto,image/jpg,image/jpeg,image/png]',
                    'max_size[img_producto,4096]'
                ]
            ]);

            if (!$validacionImg) {
                return redirect()->back()->withInput()->with('errores', $this->validator->getErrors());
            }

            $ruta_logo = './images/productos/' . $id . ".jpg";
            if (file_exists($ruta_logo)) {
                unlink($ruta_logo);
            }

            $img->move('./images/productos', $id . '.jpg');
        }

        return redirect()->to(base_url() . "/productos");
    }

    public function eliminar($id)
    {
        $this->productos->update($id, ['activo' => 0]);
        return redirect()->to(base_url('/productos'));
    }

    public function reingresar($id)
    {
        $this->productos->update($id, ['activo' => 1]);
        return redirect()->to(base_url('/productos'));
    }

    public function buscarPorcodigo($codigo)
    {
        $this->productos->select("*");
        $this->productos->where("codigo", $codigo);
        $this->productos->where("activo", 1);
        $datos = $this->productos->get()->getrow();

        $res["existe"] = false;
        $res["datos"] = "";
        $res["error"] = "";
        if ($datos) {
            $res["existe"] = true;
            $res["datos"] = $datos;
        } else {
            $res["error"] = "No existe el producto";
            $res["existe"] = false;
        }

        echo json_encode($res);

    }
    public function autocompleteData()
    {

        $returnData = array();
        $valor = $this->request->getGet('term');
        $productos = $this->productos->like('codigo', $valor)->where('activo', 1)->findAll();
        if (!empty($productos)) {

            foreach ($productos as $row) {
                $data['id'] = $row['id'];
                $data['label'] = $row['codigo'] . ' - ' . $row['nombre'];
                array_push($returnData, $data);

            }

        }
        echo json_encode($returnData);
    }

    function muestraCodigos()
    {

        echo view('cabecera');
        echo view('productos/ver_codigos');
        echo view('pie');
    }
    public function generaBarras()
    {
        $pdf = new \FPDF('P', 'mm', 'letter');
        $pdf->AddPage();
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetTitle("Codigo de barras");

        $productos = $this->productos->where('activo', 1)->findAll();
        foreach ($productos as $producto) {
            $codigo = $producto['codigo'];



            $generaBarcode = new Barcode();
            $generaBarcode->barcode("images/barcode/" . $codigo . ".png", $codigo, 20, "horizontal", "code128", true);

            $pdf->Image("images/barcode/" . $codigo . ".png");

            // unlink("images/barcode/". $codigo . "png");


        }
        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output('Codigo.pdf', 'I');
    }

    function mostrarMinimos()
    {

        echo view('cabecera');
        echo view('productos/ver_minimos');
        echo view('pie');
    }
    public function generaMinimos()
    {
        $pdf = new \FPDF('P', 'mm', 'letter');
        $pdf->AddPage();
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetTitle(utf8_decode("Productos con stock mínimo"));
        $pdf->SetFont("Arial", 'B', 10);

        $pdf->Image("images/gracias.png", 10, 5, 20);
        $pdf->Cell(0, 5, utf8_decode("Reporte de productos con stock mínimo"), 0, 1, "C");
        $pdf->Ln(25);

        $pdf->Cell(40, 5, utf8_decode("Código"), 1, 0, "C");//190
        $pdf->Cell(85, 5, utf8_decode("Nombre"), 1, 0, "C");
        $pdf->Cell(30, 5, utf8_decode("Existencias"), 1, 0, "C");
        $pdf->Cell(30, 5, utf8_decode("Stock mínimo"), 1, 1, "C");

        $datosProductos = $this->productos->getProductosMinimos();

        foreach ($datosProductos as $producto) {
            $pdf->Cell(40, 5, $producto['codigo'], 1, 0, "C");//190
            $pdf->Cell(85, 5, $producto['nombre'], 1, 0, "C");
            $pdf->Cell(30, 5, $producto['existencias'], 1, 0, "C");
            $pdf->Cell(30, 5, $producto['stock_minimo'], 1, 1, "C");

        }

        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output('ProductoMinimo.pdf', 'I');
    }
    public function mostrarMinimosExcel()
    {
        $phpExcel = new Spreadsheet();
        $phpExcel->getProperties()->setCreator("Miley")->
            setTitle("Reporte POS");

        $hoja = $phpExcel->getActiveSheet();

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName("Logo");
        $drawing->setPath("images/logo.jpg");
        $drawing->setHeight(80);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($hoja);


        $hoja->mergeCells("A3:D3");
        $hoja->getStyle('A3')->getAlignment()->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        );
        $hoja->getStyle('A3')->getFont()->setSize(14);
        $hoja->getStyle('A3')->getFont()->setName('Arial');
        $hoja->setCellValue('A3', 'Reporte de productos con stock mínimo');

        $hoja->setCellValue('A5', 'Código');
        $hoja->getColumnDimension('A')->setWidth(20);
        $hoja->setCellValue('B5', 'Nombre');
        $hoja->getColumnDimension('B')->setWidth(40);
        $hoja->setCellValue('C5', 'Existencias');
        $hoja->getColumnDimension('C')->setWidth(20);
        $hoja->setCellValue('D5', 'Stock');
        $hoja->getColumnDimension('D')->setWidth(20);
        $hoja->getStyle('A5:D5')->getFont()->setBold(true);

        $datosProductos = $this->productos->getProductosMinimos();

        $fila = 6;
        foreach ($datosProductos as $producto) {

            $hoja->setCellValue('A' . $fila, $producto['codigo']);
            $hoja->setCellValue('B' . $fila, $producto['nombre']);
            $hoja->setCellValue('C' . $fila, $producto['existencias']);
            $hoja->setCellValue('D' . $fila, $producto['stock_minimo']);
            $fila++;

        }

        $ultimaFila = $fila - 1;
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '000000'],
                ]
            ]
        ];


        $hoja->getStyle('A5:D' . $ultimaFila)->applyFromArray($styleArray);

        $hoja->setCellValue('C' . $fila, '=SUM(C6:C' . $ultimaFila . ')');


        $writer = new Xlsx($phpExcel);
        $writer->save('reporte.xlsx');


    }

}