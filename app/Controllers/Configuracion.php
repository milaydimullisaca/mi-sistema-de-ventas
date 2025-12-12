<?php
namespace App\Controllers;

use App\Models\ConfiguracionModel;

class Configuracion extends BaseController
{
    protected $configuracion;

    public function __construct()
    {
        $this->configuracion = new ConfiguracionModel();
        helper(["form", 'upload']);
    }

    public function index($activo = 1)
    {
        $nombre = $this->configuracion->where('nombre', "tienda_nombre")->first();
        $rfc = $this->configuracion->where('nombre', "tienda_rfc")->first();
        $telefono = $this->configuracion->where('nombre', "tienda_telefono")->first();
        $email = $this->configuracion->where('nombre', "tienda_email")->first();
        $direccion = $this->configuracion->where('nombre', "tienda_direccion")->first();
        $leyenda = $this->configuracion->where('nombre', "ticket_leyenda")->first();

        $data = [
            'titulo' => 'Configuracion',
            "nombre" => $nombre,
            "rfc" => $rfc,
            "telefono" => $telefono,
            "email" => $email,
            "direccion" => $direccion,
            "leyenda" => $leyenda
        ];

        echo view('cabecera');
        echo view('configuracion/configuracion', $data);
        echo view('pie');
    }

    public function actualizar()
{
    
    $reglas = [
        'tienda_nombre' => [
            'rules' => 'required',
            'errors' => ['required' => 'El nombre es obligatorio.']
        ],
        'tienda_rfc' => [
            'rules' => 'required',
            'errors' => ['required' => 'El RFC es obligatorio.']
        ]
    ];

    if (!$this->validate($reglas)) {
        return redirect()->back()
            ->withInput()
            ->with('errores', $this->validator->getErrors());
    }

    $campos = ['tienda_nombre', 
    'tienda_rfc', 'tienda_telefono', 
    'tienda_email', 'tienda_direccion', 
    'ticket_leyenda'];

    foreach ($campos as $campo) {
        $this->configuracion->where('nombre', $campo)
            ->set(['valor' => $this->request->getPost($campo)])
            ->update();
    }

    return redirect()->to(base_url() . '/configuracion');
}
}