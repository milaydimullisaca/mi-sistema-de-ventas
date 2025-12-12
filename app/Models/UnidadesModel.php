<?php
namespace App\Models;
use CodeIgniter\Model;

class UnidadesModel extends Model
{
    protected $table      = 'unidades';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = ['nombre', 'nombre_corto', 'activo'];

    // Evitar inserciones vacías y actualizar solo cambios
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';  // <- corregido
    protected $createdField  = 'fecha_alta'; // fecha de creación
    protected $updatedField  = 'fecha_edit'; // fecha de actualización
}
?>
