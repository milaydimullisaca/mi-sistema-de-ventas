<?php
namespace App\Controllers;

use App\Controllers\BaseControllers;
use App\Models\CajasModel;
use App\Models\ArqueoCajaModel;
use App\Models\VentasModel;


class Cajas extends BaseController
{
    protected $cajas, $arqueoModel,$ventasModel;


    public function __construct()
    {
        $this->cajas = new CajasModel();
        $this->arqueoModel = new ArqueoCajaModel();
        $this->ventasModel = new VentasModel();

    }

    public function index($activo = 1)
    {
        $cajas = $this->cajas->where('activo', $activo)->findAll();

        $data = [
            'titulo' => 'cajas',
            'datos' => $cajas
        ];

        echo view('cabecera');
        echo view('cajas/cajas', $data);
        echo view('pie');
    }

    public function eliminados($activo = 0)
    {
        $cajas = $this->cajas->where('activo', $activo)->findAll();

        $data = [
            'titulo' => 'cajas eliminados',
            'datos' => $cajas
        ];

        echo view('cabecera');
        echo view('cajas/eliminados', $data);
        echo view('pie');
    }

    public function nuevo()
    {


        $data = [
            'titulo' => 'Nueva caja',

        ];

        echo view('cabecera');
        echo view('cajas/nuevo', $data);
        echo view('pie');
    }

    public function insertar()
    {
        $validation = \Config\Services::validation();

        $reglas = [

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
        if ($this->request->getMethod() === 'POST') {
            $this->cajas->save([


                'nombre' => $this->request->getPost('nombre'),
                'folio' => $this->request->getPost('folio'),
                'activo' => 1

            ]);

            return redirect()->to(base_url() . "/cajas");

        } else {
            $data = ['titulo' => 'Nueva caja', "validation" => $this->validator];
            echo view('cabecera');
            echo view('cajas/nuevo', $data);
            echo view('pie');
        }
    }
    public function editar($id)
    {

        $cajas = $this->cajas->where('id', $id)->first();
        $data = [
            'titulo' => 'Modificar caja',

            'cajas' => $cajas
        ];


        echo view('cabecera');
        echo view('cajas/editar', $data);
        echo view('pie');
    }

    public function actualizar()
    {
        $id = $this->request->getPost('id');

        $reglas = [

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


        $this->cajas->update($this->request->getPost('id'), [

            'nombre' => $this->request->getPost('nombre'),
            'folio' => $this->request->getPost('folio'),
            'activo' => 1

        ]);


        return redirect()->to(base_url('/cajas'));
    }

    public function eliminar($id)
    {
        $this->cajas->update($id, ['activo' => 0]);
        return redirect()->to(base_url('/cajas'));
    }

    public function reingresar($id)
    {
        $this->cajas->update($id, ['activo' => 1]);
        return redirect()->to(base_url('/cajas'));
    }
    public function arqueo($idCaja)
    {
        $arqueos = $this->arqueoModel->getDatos($idCaja);
        $data = ['titulo' => 'Cierre de caja', 'datos' => $arqueos];

        echo view('cabecera');
        echo view('cajas/arqueo', $data);
        echo view('pie');
    }

    public function nuevo_arqueo()
    {
        $session = session();

        $existe = $this->arqueoModel->where(['id_caja' => $session->id_caja, 'estatus' => 1])
            ->countAllResults();
        if ($existe > 0) {
            echo 'La caja ya esta abierta';
            exit;
        }

        if ($this->request->getMethod() == 'POST') {

            $fecha = date('Y-m-d H:i:s');
            $existe = 0;


            $this->arqueoModel->save([
                'id_caja' => $session->id_caja,
                'id_usuario' => $session->id_usuario,
                'fecha_inicio' => $fecha,
                'monto_inicial' => $this->request->getPost('monto_inicial'),
                'estatus' => 1
            ]);

            return redirect()->to(base_url('/cajas'));

        } else {

            $caja = $this->cajas->where('id', $session->id_caja)->first();
            $data = ['titulo' => 'Apertura de caja', 'caja' => $caja];

            echo view('cabecera');
            echo view('cajas/nuevo_arqueo', $data);
            echo view('pie');
        }
    }

    public function cerrar()
{
    $session = session();

    if ($this->request->getMethod() == 'POST') {

        $fecha = date('Y-m-d H:i:s');

        $this->arqueoModel->update($this->request->getPost('id_arqueo'),[
            'fecha_fin' => $fecha,
            'monto_final' => $this->request->getPost('monto_final'),
            'total_ventas' => $this->request->getPost('total_ventas'),
            'estatus' => 0
        ]);

        return redirect()->to(base_url('/cajas'));

    } else {

        $montoTotal= $this->ventasModel->totalDia(date('Y-m-d'));
        $arqueo = $this->arqueoModel->where(['id_caja' => $session->id_caja, 'estatus' => 1])
            ->first();
        $caja = $this->cajas->where('id', $session->id_caja)->first();
        $data = [
            'titulo' => 'Cierre de caja',
            'caja' => $caja,
            'arqueo' => $arqueo,
            'session' => $session,
            'monto'=>$montoTotal
        ];

        echo view('cabecera');
        echo view('cajas/cerrar', $data);
        echo view('pie');
    }
}

}