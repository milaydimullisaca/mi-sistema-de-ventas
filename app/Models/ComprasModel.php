<?php
namespace App\Models;
use CodeIgniter\Model;

class ComprasModel extends Model
{
    protected $table      = 'compras';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = ['folio', 'total', 'id_usuario','activo'];

    
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';  
    protected $createdField  = 'fecha_alta';
    protected $updatedField  = ''; 
    protected $deletedField = '';

    protected $validationRules = [] ;
    protected $validationMessages = [];
    protected $skipValidation = false;


   public function insertaCompra($id_compra, $total, $id_usuario)
{
    $this->insert([
        'folio' => $id_compra,
        'total' => $total,
        'id_usuario' => $id_usuario
    ]);
    return $this->insertID();
}

}
