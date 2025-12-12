<?php

namespace App\Controllers;
use App\Models\ProductosModel;
use App\Models\VentasModel;


class Inicio extends BaseController
{
    protected $productosModel, $ventasModel;

    public function __construct()
    {
        $this->productosModel = new ProductosModel();
        $this->ventasModel = new VentasModel();
    }

    public function index()
    {
        $session = session();

        if (!$session->has('id_usuario')) {
            return redirect()->to(base_url('/login'))->with('error', 'Debes iniciar sesión');
        }


        $total = $this->productosModel->totalProductos();
        $minimos = $this->productosModel->productosMinimos();
        $hoy = date('Y-m-d');
        $totalVentas = $this->ventasModel->totalDia($hoy);

        $datos = [
            'total' => $total,
            'totalVentas' => $totalVentas,
            'minimos' => $minimos,
        ];

        echo view('cabecera');
        echo view('inicio', $datos);
        echo view('pie');
    }

}
