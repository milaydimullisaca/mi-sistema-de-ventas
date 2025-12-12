<?php
namespace App\Models;
use CodeIgniter\Model;

class ConfiguracionModel extends Model
{
    protected $table      = 'configuracion';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nombre', 'valor'];

    // DESACTIVAR TIMESTAMPS (tu tabla NO los tiene)
    protected $useTimestamps = false;
    protected $createdField = '';
    protected $updatedField = '';
    protected $deletedField = '';


    protected $validationRules = [];
    protected $validationMessages = [];
}

