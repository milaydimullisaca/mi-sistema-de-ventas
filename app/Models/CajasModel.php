<?php
namespace App\Models;
use CodeIgniter\Model;

class CajasModel extends Model
{
    protected $table      = 'cajas';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [ 'numero_caja','nombre', 'folio','activo'];

    
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

  
    protected $useTimestamps = true;
    protected $createdField  = 'fecha_alta'; 
    protected $updatedField  = 'fecha_modifica'; 
    protected $deletedField = 'deleted_at';
}
?>
