<?php
namespace App\Controllers;

use App\Controllers\BaseControllers;
use App\Models\ClientesModel;


class Clientes extends BaseController
{
    protected $clientes;


    public function __construct()
    {
        $this->clientes = new ClientesModel();

    }

    public function index($activo = 1)
    {
        $clientes = $this->clientes->where('activo', $activo)->findAll();

        $data = [
            'titulo' => 'Clientes',
            'datos' => $clientes
        ];

        echo view('cabecera');
        echo view('clientes/clientes', $data);
        echo view('pie');
    }

    public function eliminados($activo = 0)
    {
        $clientes = $this->clientes->where('activo', $activo)->findAll();

        $data = [
            'titulo' => 'Clientes eliminados',
            'datos' => $clientes
        ];

        echo view('cabecera');
        echo view('clientes/eliminados', $data);
        echo view('pie');
    }

    public function nuevo()
    {


        $data = [
            'titulo' => 'Agregar cliente',

        ];

        echo view('cabecera');
        echo view('clientes/nuevo', $data);
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
            $this->clientes->save([


                'nombre' => $this->request->getPost('nombre'),
                'direccion' => $this->request->getPost('direccion'),
                'telefono' => $this->request->getPost('telefono'),
                'correo' => $this->request->getPost('correo'),

            ]);

            return redirect()->to(base_url() . "/clientes");

        } else {
            $data = ['titulo' => 'Agregar cliente', "validation" => $this->validator];
            echo view('cabecera');
            echo view('clientes/nuevo', $data);
            echo view('pie');
        }
    }
    public function editar($id)
    {

        $clientes = $this->clientes->where('id', $id)->first();
        $data = [
            'titulo' => 'Editar cliente',

            'clientes' => $clientes
        ];


        echo view('cabecera');
        echo view('clientes/editar', $data);
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


        $this->clientes->update($this->request->getPost('id'), [

            'nombre' => $this->request->getPost('nombre'),
            'direccion' => $this->request->getPost('direccion'),
            'telefono' => $this->request->getPost('telefono'),
            'correo' => $this->request->getPost('correo'),
        ]);


        return redirect()->to(base_url('/clientes'));
    }

    public function eliminar($id)
    {
        $this->clientes->update($id, ['activo' => 0]);
        return redirect()->to(base_url('/clientes'));
    }

    public function reingresar($id)
    {
        $this->clientes->update($id, ['activo' => 1]);
        return redirect()->to(base_url('/clientes'));
    }
    public function autocompleteData()
    {

        $returnData = array();
        $valor = $this->request->getGet('term');
        $clientes = $this->clientes->like('nombre', $valor)->where('activo', 1)->findAll();
        if (!empty($clientes)) {

            foreach ($clientes as $row) {
                $data['id'] = $row['id'];
                $data['value'] = $row['nombre'];
                array_push($returnData, $data);

            }

        }
        echo json_encode($returnData);
    }
}
