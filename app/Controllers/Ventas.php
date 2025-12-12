<?php
namespace App\Controllers;

use \App\Models\TemporalComprasModel;
use App\Models\VentasModel;
use App\Models\DetalleVentaModel;
use App\Models\ProductosModel;
use App\Models\ConfiguracionModel;
use App\Models\CajasModel;


class Ventas extends BaseController
{
    protected $ventas;
    protected $temporal_compra;
    protected $detalle_venta;
    protected $productos;
    protected $configuracion;
    protected $cajas;


    public function __construct()
    {
        $this->ventas = new VentasModel();
        $this->detalle_venta = new DetalleVentaModel();
        $this->productos = new ProductosModel();
        $this->configuracion = new ConfiguracionModel();
        $this->temporal_compra = new TemporalComprasModel();
        $this->cajas = new CajasModel();

        helper('form');
    }

    public function index()
    {
         $session = session();
    if (!$session->has('id_usuario')) {
        return redirect()->to(base_url('/login'))->with('error', 'Debes iniciar sesión');
    }
       $datos = $this->ventas->obtener(1);
        $data = [
            'titulo' => 'Ventas',
            'datos' => $datos
        ];

        echo view('cabecera');
        echo view('ventas/ventas', $data);
        echo view('pie');
    }

     public function eliminados()
    {
       $datos = $this->ventas->obtener(0);
        $data = [
            'titulo' => 'Ventas',
            'datos' => $datos
        ];

        echo view('cabecera');
        echo view('ventas/eliminados', $data);
        echo view('pie');
    }
    public function venta()
    {
        echo view('cabecera');
        echo view('ventas/caja');
        echo view('pie');
    }

    public function guarda()
{
    $session = session();

    if (!$session->has('id_usuario') || !$session->has('id_caja')) {
        return redirect()->to(base_url('/login'))->with('error', 'Debes iniciar sesión');
    }

    $id_usuario = $session->get('id_usuario');
    $id_caja = $session->get('id_caja');

    $id_venta = $this->request->getPost('id_venta');
    $rawTotal = str_replace(',', '', $this->request->getPost('total'));
    $total = floatval($rawTotal);

    $forma_pago = $this->request->getPost('forma_pago');
    $id_cliente = $this->request->getPost('id_cliente');

    $caja = $this->cajas->where('id', $id_caja)->first();
    $folio = $caja['folio'];

    $resultadoId = $this->ventas->insertaVenta(
        $folio,
        $total,
        $id_usuario,
        $id_caja,
        $id_cliente,
        $forma_pago
    );

    $folio++;
    $this->cajas->update($id_caja, ['folio' => $folio]);

    $resultadoCompra = $this->temporal_compra->porCompra($id_venta);

    foreach ($resultadoCompra as $row) {
        $this->detalle_venta->save([
            "id_venta" => $resultadoId,
            "id_producto" => $row["id_producto"],
            "nombre" => $row["nombre"],
            "cantidad" => $row["cantidad"],
            "precio" => $row["precio"],
        ]);

        $this->productos->actualizaStock($row["id_producto"], $row["cantidad"], '-');
    }

    $this->temporal_compra->eliminarCompra($id_venta);

    return redirect()->to(base_url('ventas/muestra-ticket-pdf/' . $resultadoId));
}
    function muestraTicketPdf($id_venta)
    {
        $data['id_venta'] = $id_venta;
        echo view('cabecera');
        echo view('ventas/ver_ticket_pdf', $data);
        echo view('pie');
    }

function generarTicketPdf($id_venta)
{
    $datosVenta = $this->ventas->where('id', $id_venta)->first();
    $detalleVenta = $this->detalle_venta->select('*')->where('id_venta', $id_venta)->findAll();
    $nombreTienda = $this->configuracion->select('valor')->where('nombre', 'tienda_nombre')->get()->getRow()->valor;
    $direccionTienda = $this->configuracion->select('valor')->where('nombre', 'tienda_direccion')->get()->getRow()->valor;
    $leyendaTicket = $this->configuracion->select('valor')->where('nombre', 'ticket_leyenda')->get()->getRow()->valor;

    $pdf = new \FPDF('P', 'mm', array(80, 200));
    $pdf->AddPage();
    $pdf->SetMargins(5, 5, 5);
    $pdf->SetTitle("Venta");

    $pdf->SetFont("Arial", 'B', 10);
    $pdf->Cell(70, 5, utf8_decode($nombreTienda), 0, 1, 'C');

    $pdf->SetFont("Arial", 'B', 9);
    $pdf->image(base_url() . 'images/logo.jpg', 5, 0, 20, 20, 'JPG');
    $pdf->SetFont("Arial", '', 9);
    $pdf->Cell(70, 5, utf8_decode($direccionTienda), 0, 1, 'C');

    $fechaVenta = $datosVenta['fecha_alta'];
    $fecha = new \DateTime($fechaVenta, new \DateTimeZone('UTC')); 
    $fecha->setTimezone(new \DateTimeZone('America/Lima'));
    $fechaTicket = $fecha->format('d/m/Y H:i:s');

    $pdf->SetFont("Arial", 'B', 9);
    $pdf->Cell(25, 5, utf8_decode('Fecha y hora:'), 0, 0, 'L');
    $pdf->SetFont("Arial", '', 9);
    $pdf->Cell(50, 5, $fechaTicket, 0, 1, 'L');
 
    $pdf->SetFont("Arial", 'B', 9);
    $pdf->Cell(15, 5, utf8_decode('Ticket:'), 0, 0, 'L');
    $pdf->SetFont("Arial", '', 9);
    $pdf->Cell(50, 5, $datosVenta['folio'], 0, 1, 'L');

    $pdf->Ln(5);

    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(7, 5, utf8_decode('Cant.'), 0, 0, 'L');
    $pdf->Cell(35, 5, utf8_decode('Nombre'), 0, 0, 'L');
    $pdf->Cell(15, 5, utf8_decode('Precio'), 0, 0, 'L');
    $pdf->Cell(15, 5, utf8_decode('Importe'), 0, 1, 'L');
    $pdf->SetFont('Arial', '', 7);

    $totalArticulos = 0;
    foreach ($detalleVenta as $row) {
        $pdf->Cell(7, 5, $row['cantidad'], 0, 0, 'L');
        $pdf->Cell(35, 5, utf8_decode($row['nombre']), 0, 0, 'L');
        $pdf->Cell(15, 5, '$ ' . number_format($row['precio'], 2, '.', ','), 0, 0, 'L');
        $importe = number_format($row['precio'] * $row['cantidad'], 2, '.', ',');
        $pdf->Cell(15, 5, '$ ' . $importe, 0, 1, 'R');

        $totalArticulos += $row['cantidad'];
    }

    $pdf->Ln();

    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(70, 5, utf8_decode('Total de artículos: ') . $totalArticulos, 0, 1, 'R');

    // Total $ de la venta
    $pdf->Cell(70, 5, 'Total $ ' . number_format($datosVenta['total'], 2, '.', ','), 0, 1, 'R');

    $pdf->Ln();
    // Leyenda del ticket
    $pdf->MultiCell(70, 4, utf8_decode($leyendaTicket), 0, 'C', 0);

    // Mostrar PDF
    $this->response->setHeader('Content-Type', 'application/pdf');
    $pdf->Output("ticket_pdf.pdf", "I");
}


    public function eliminar($id){
        $productos = $this->detalle_venta->where('id_venta', $id)->findAll();
        foreach($productos as $producto){
            $this->productos->actualizaStock($producto['id_producto'], $producto['cantidad'], '+' ) ;

    }
    $this->ventas->update($id,['activo'=>0]);
    return redirect()->to(base_url().'/ventas');
}
}


