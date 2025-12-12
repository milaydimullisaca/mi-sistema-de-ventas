<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuariosModel;
use App\Models\CajasModel;
use App\Models\RolesModel;
use App\Models\LogsModel;

class Usuarios extends BaseController
{
    protected $usuarios;
    protected $cajas;
    protected $roles;
    protected $logs;
    protected $reglas;
    protected $reglaslogin;
    protected $reglascambia;


    public function __construct()
    {
        helper('form');

        $this->usuarios = new UsuariosModel();
        $this->cajas = new CajasModel();
        $this->roles = new RolesModel();
        $this->logs = new LogsModel();

        $this->reglas = [
            'usuario' => [
                'rules' => 'required|is_unique[usuarios.usuario]',
                'errors' => [
                    'required' => 'El usuario es obligatorio.',
                    'is_unique' => 'Este usuario ya existe.'
                ]
            ],
            'nombre' => [
                'rules' => 'required',
                'errors' => ['required' => 'El nombre es obligatorio.']
            ],
            'password' => [
                'rules' => 'required',
                'errors' => ['required' => 'La contraseña es obligatoria.']
            ],
            'repassword' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Debes repetir la contraseña.',
                    'matches' => 'Las contraseñas no coinciden.'
                ]
            ],
            'id_caja' => [
                'rules' => 'required',
                'errors' => ['required' => 'Debes seleccionar la caja.']
            ],
            'id_rol' => [
                'rules' => 'required',
                'errors' => ['required' => 'Debes seleccionar el rol.']
            ]
        ];
        $this->reglaslogin = [
            'usuario' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El usuario es obligatorio.',

                ]
            ],

            'password' => [
                'rules' => 'required',
                'errors' => ['required' => 'La contraseña es obligatoria.']
            ]
        ];
        $this->reglascambia = [
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'La contraseña es obligatoria.'
                ]

            ],

            'repassword' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Debes repetir la contraseña.',
                    'matches' => 'Las contraseñas no coinciden.'
                ]
            ]
        ];
    }


    public function index($activo = 1)
    {
        $usuarios = $this->usuarios->where('activo', $activo)->findAll();

        $data = [
            'titulo' => 'Usuarios',
            'datos' => $usuarios
        ];

        echo view('cabecera');
        echo view('usuarios/usuarios', $data);
        echo view('pie');
    }

  
    public function nuevo()
    {
        $data = [
            'titulo' => 'Agregar usuario',
            'cajas' => $this->cajas->where('activo', 1)->findAll(),
            'roles' => $this->roles->where('activo', 1)->findAll()
        ];

        echo view('cabecera');
        echo view('usuarios/nuevo', $data);
        echo view('pie');
    }


    public function insertar()
    {
        if (!$this->validate($this->reglas)) {

            $data = [
                'titulo' => 'Agregar usuario',
                'validation' => $this->validator,
                'cajas' => $this->cajas->where('activo', 1)->findAll(),
                'roles' => $this->roles->where('activo', 1)->findAll()
            ];

            echo view('cabecera');
            echo view('usuarios/nuevo', $data);
            echo view('pie');
            return;
        }

        $this->usuarios->save([
            'usuario' => $this->request->getPost('usuario'),
            'nombre' => $this->request->getPost('nombre'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'id_caja' => $this->request->getPost('id_caja'),
            'id_rol' => $this->request->getPost('id_rol'),
            'activo' => 1
        ]);

        return redirect()->to('/usuarios');
    }
    public function editar($id)
    {
        $unidades = $this->cajas->where('activo', 1)->findAll();
        $categorias = $this->roles->where('activo', 1)->findAll();
        $usuarios = $this->usuarios->where('id', $id)->first();
        $data = [
            'titulo' => 'Editar producto',

        ];


        echo view('cabecera');
        echo view('usuarios/editar', $data);
        echo view('pie');
    }

    public function actualizar()
    {
        $id = $this->request->getPost('id');

        $reglas = [
            'codigo' => [
                'rules' => "required|is_unique[usuarios.codigo,id,{$id}]",
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


        $this->usuarios->update($this->request->getPost('id'), [

            'nombre' => $this->request->getPost('nombre'),

        ]);


        return redirect()->to(base_url('/usuarios'));
    }

    public function eliminar($id)
    {
        $this->usuarios->update($id, ['activo' => 0]);
        return redirect()->to(base_url('/usuarios'));
    }

    public function reingresar($id)
    {
        $this->usuarios->update($id, ['activo' => 1]);
        return redirect()->to(base_url('/usuarios'));
    }


    public function login()
    {
        return view('login');
    }

    public function valida()
    {
        if ($this->request->getMethod() == 'POST' && $this->validate($this->reglaslogin)) {

            $usuario = $this->request->getPost('usuario');
            $password = $this->request->getPost('password');
            $user = $this->usuarios->where('usuario', $usuario)->first();

            if ($user != null) {
                if (password_verify($password, $user['password'])) {
                    $datosSesion = [
                        'id_usuario' => $user['id'],
                        'nombre' => $user['nombre'],
                        'id_caja' => $user['id_caja'],
                        'id_rol' => $user['id_rol']

                    ];

                    $ip=$_SERVER['REMOTE_ADDR'];
                    $detalles=$_SERVER['HTTP_USER_AGENT'];
                    $this->logs->save([
                        'id_usuario'=> $user['id'],
                        'evento'=> 'Inicio de sesión',
                        'ip'=> $ip,
                        'detalles'=> $detalles


                    ]);

                    $session = session();
                    $session->set($datosSesion);
                    return redirect()->to(base_url() . '/inicio');
                } else {
                    $data['error'] = "Las contraseñas no coinciden ";
                    echo view('login', $data);

                }
            } else {

                $data['error'] = "El usuario no existe ";
                echo view('login', $data);
            }

        } else {
            $data["validation"] = $this->validator;
            echo view('login', $data);
        }
    }

    public function logout()
    {
        $session = session();
          $ip=$_SERVER['REMOTE_ADDR'];
                    $detalles=$_SERVER['HTTP_USER_AGENT'];
                    $this->logs->save([
                        'id_usuario'=> $session->id_usuario,
                        'evento'=> 'Cierre de sesión',
                        'ip'=> $ip,
                        'detalles'=> $detalles


                    ]);
        $session->destroy();
        return redirect()->to(base_url());
    }
    public function cambia_password()
    {
        $session = session();
        $usuario = $this->usuarios->where('id', $session->id_usuario)->first();
        $data = [
            'titulo' => 'Cambiar contraseña',
            "usuario" => $usuario
        ];

        echo view('cabecera');
        echo view('usuarios/cambia_password', $data);
        echo view('pie');
    }
    public function actualizar_password()
    {
        $session = session();
        $idUsuario = $session->id_usuario;

        // Validar formulario
        if (!$this->validate($this->reglascambia)) {
            $usuario = $this->usuarios->where('id', $idUsuario)->first();
            $data = [
                'titulo' => 'Cambiar contraseña',
                "usuario" => $usuario,
                "validation" => $this->validator // Para mostrar errores
            ];

            echo view('cabecera');
            echo view('usuarios/cambia_password', $data);
            echo view('pie');
            return;
        }

        // Guardar la nueva contraseña
        $hash = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

        $this->usuarios->update($idUsuario, [
            'password' => $hash
        ]);

        // Mostrar mensaje de éxito
        $usuario = $this->usuarios->where('id', $idUsuario)->first();
        $data = [
            'titulo' => 'Cambiar contraseña',
            "usuario" => $usuario,
            "mensaje" => "Contraseña actualizada correctamente"
        ];

        echo view('cabecera');
        echo view('usuarios/cambia_password', $data);
        echo view('pie');
    }

}
