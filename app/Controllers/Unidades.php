<?php
namespace App\Controllers;

use App\Models\UnidadesModel;

class Unidades extends BaseController
{
    protected $unidades;

    public function __construct()
    {
        $this->unidades = new UnidadesModel();
    }

    public function index($activo=1)
    {
        $unidades= $this->unidades->where('activo', $activo)->findAll();
        $data = [
            'titulo' => 'Unidades',
            'datos' => $this->unidades->where('activo', 1)->findAll()
        ];

        echo view('cabecera');
        echo view('unidades/unidades', $data);
        echo view('pie');
    }
    public function eliminados($activo = 0)
    {
        $unidades = $this->unidades->where('activo', $activo)->findAll();
        $data = [
            'titulo' => 'Unidades eliminadas',
            'datos' => $unidades
        ];

        echo view('cabecera');
        echo view('unidades/eliminados', $data);
        echo view('pie');
    }


    public function nuevo()
    {
        $data = ['titulo' => 'Agregar unidad'];
        echo view('cabecera');
        echo view('unidades/nuevo', $data);
        echo view('pie');
    }

    public function insertar()
    {
        if ($this->request->getMethod() == 'POST'&& $this->validate([
            'nombre' => "required",'nombre_corto' => 'required' ])) {
        $this->unidades->save([
            'nombre' => $this->request->getPost('nombre'),
            'nombre_corto' => $this->request->getPost('nombre_corto'),
            'activo' => 1
        ]);
        return redirect()->to(base_url() . '/unidades');
    } else {
        $data = ['titulo' => 'Agregar unidad' ,"validation"=> $this->validator];
        echo view('cabecera');
        echo view('unidades/nuevo');
        echo view('pie');
        
    }
    }

    public function editar($id)
    {
        $dato = $this->unidades->where('id', $id)->first();
        $data = ['titulo' => 'Editar unidad', 'dato' => $dato];

        echo view('cabecera');
        echo view('unidades/editar', $data);
        echo view('pie');
    }
    public function actualizar()
    {
        $id = $this->request->getPost('id');

        $this->unidades->update($id, [
            'nombre' => $this->request->getPost('nombre'),
            'nombre_corto' => $this->request->getPost('nombre_corto')
        ]);

        return redirect()->to(base_url() . '/unidades');
    }
    public function eliminar($id)
    {

        $this->unidades->update($id, ['activo' => 0]);

        return redirect()->to(base_url() . '/unidades');
    }
    public function reingresar($id)
    {

        $this->unidades->update($id, ['activo' => 1]);

        return redirect()->to(base_url() . '/unidades');
    }
}