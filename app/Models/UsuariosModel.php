<?php
namespace App\Models;
use CodeIgniter\Model;

class UsuariosModel extends Model
{
    protected $table      = 'usuarios';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = ['usuario', 'password',
        
        "nombre", 'id_caja',"id_rol","activo"];


    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

  
    protected $useTimestamps = true;

    protected $createdField  = 'fecha_alta'; 
    protected $updatedField  = 'fecha_modifica'; 
    
}
?>
