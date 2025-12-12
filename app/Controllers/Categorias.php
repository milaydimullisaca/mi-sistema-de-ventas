<?php
namespace App\Controllers;

use App\Models\CategoriasModel;

class Categorias extends BaseController
{
    protected $categorias;

    public function __construct()
    {
        $this->categorias = new CategoriasModel();
    }

    public function index($activo = 1)
    {
        $categorias = $this->categorias->where('activo',$activo)->findAll();
        $data = [
            'titulo' => 'Categorias',
            'datos' => $this->categorias->where('activo', 1)->findAll()
        ];

        echo view('cabecera');
        echo view('categorias/categorias', $data);
        echo view('pie');
    }
    public function eliminados($activo = 0)
    {
        $categorias = $this->categorias->where('activo', $activo)->findAll();
        $data = [
            'titulo' => 'Categorias eliminadas',
            'datos' => $categorias
        ];

        echo view('cabecera');
        echo view('categorias/eliminados', $data);
        echo view('pie');
    }


    public function nuevo()
    {
        $data = ['titulo' => 'Agregar categoria'];
        echo view('cabecera');
        echo view('categorias/nuevo', $data);
        echo view('pie');
    }

    public function insertar()
    {
        $this->categorias->save([
            'nombre' => $this->request->getPost('nombre'),
            'activo' => 1
        ]);

        return redirect()->to(base_url() . '/categorias');
    }


    public function editar($id)
    {
        $dato = $this->categorias->where('id', $id)->first();
        $data = ['titulo' => 'Editar categoria', 'dato' => $dato];

        echo view('cabecera');
        echo view('categorias/editar', $data);
        echo view('pie');
    }
    public function actualizar()
    {
        $id = $this->request->getPost('id');

        $this->categorias->update($id, [
            'nombre' => $this->request->getPost('nombre'),
        ]);

        return redirect()->to(base_url() . '/categorias');
    }
    public function eliminar($id)
    {

        $this->categorias->update($id, ['activo' => 0]);

        return redirect()->to(base_url() . '/categorias');
    }
    public function reingresar($id)
    {

        $this->categorias->update($id, ['activo' => 1]);

        return redirect()->to(base_url() . '/categorias');
    }
}