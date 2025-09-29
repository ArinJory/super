<?php
namespace App\Models;

use CodeIgniter\Model;

class SuperheroModel extends Model
{
    protected $table = 'superhero';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name','gender','weight_kg','publisher'];

  
    public function getFiltered($filters = [], $limit = 50)
    {
        $builder = $this->builder();

        if (!empty($filters['title'])) {
            $builder->like('name', $filters['title']);
        }
        if (!empty($filters['genders'])) {
            $builder->whereIn('gender', $filters['genders']);
        }
        if (!empty($filters['publishers'])) {
            $builder->whereIn('publisher', $filters['publishers']);
        }

        $builder->limit(min(max($limit,10),200)); // entre 10 y 200
        return $builder->get()->getResultArray();
    }

   
    public function aggregate($metric = 'count', $filters = [])
    {
        $builder = $this->builder();
        if ($metric === 'avg_weight') {
            $builder->select('publisher, AVG(weight_kg) as value');
        } else {
            $builder->select('publisher, COUNT(*) as value');
        }

        if (!empty($filters['genders'])) {
            $builder->whereIn('gender', $filters['genders']);
        }
        if (!empty($filters['publishers'])) {
            $builder->whereIn('publisher', $filters['publishers']);
        }

        $builder->groupBy('publisher');

        if ($metric === 'avg_weight') {
            $builder->orderBy('value','ASC'); // Ejercicio 3
        }
        return $builder->get()->getResultArray();
    }
}
