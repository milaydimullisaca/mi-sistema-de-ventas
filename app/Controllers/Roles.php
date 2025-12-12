<?php
namespace App\Controllers;

use App\Controllers\BaseControllers;
use App\Models\RolesModel;


class Roles extends BaseController
{
    protected $roles;


    public function __construct()
    {
        $this->roles = new RolesModel();

    }

    public function index($activo = 1)
    {
        $roles = $this->roles->where('activo', $activo)->findAll();

        $data = [
            'titulo' => 'Roles',
            'datos' => $roles
        ];

        echo view('cabecera');
        echo view('roles/roles', $data);
        echo view('pie');
    }

    public function eliminados($activo = 0)
    {
        $roles = $this->roles->where('activo', $activo)->findAll();

        $data = [
            'titulo' => 'Roles eliminados',
            'datos' => $roles
        ];

        echo view('cabecera');
        echo view('roles/eliminados', $data);
        echo view('pie');
    }

    public function nuevo()
    {


        $data = [
            'titulo' => 'Nuevo rol',

        ];

        echo view('cabecera');
        echo view('roles/nuevo', $data);
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
            $this->roles->save([


                'nombre' => $this->request->getPost('nombre'),
                'activo' => 1
                

            ]);

            return redirect()->to(base_url() . "/roles");

        } else {
            $data = ['titulo' => 'Nuevo rol', "validation" => $this->validator];
            echo view('cabecera');
            echo view("roles/nuevo" , $data);
            echo view('pie');
        }
    }
    public function editar($id)
    {

        $roles = $this->roles->where('id', $id)->first();
        $data = [
            'titulo' => 'Editar rol',

            'roles' => $roles
        ];


        echo view('cabecera');
        echo view('roles/editar', $data);
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


        $this->roles->update($this->request->getPost('id'), [

            'nombre' => $this->request->getPost('nombre'),
            'activo' => 1
           
        ]);


        return redirect()->to(base_url('/roles'));
    }

    public function eliminar($id)
    {
        $this->roles->update($id, ['activo' => 0]);
        return redirect()->to(base_url('/roles'));
    }

    public function reingresar($id)
    {
        $this->roles->update($id, ['activo' => 1]);
        return redirect()->to(base_url('/roles'));
    }
}
