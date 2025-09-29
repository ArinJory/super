<?php
namespace App\Controllers;

use App\Models\SuperheroModel;
use CodeIgniter\Controller;

class Superheroes extends Controller
{
    public function index()
    {
        $model = new SuperheroModel();
        $publishers = $model->select('DISTINCT publisher')->findAll();
        return view('superheroes/index', ['publishers' => $publishers]);
    }


    public function apiSuperheroes()
    {
        $model = new SuperheroModel();
        $filters = [
            'title' => $this->request->getGet('title'),
            'genders' => $this->request->getGet('genders') ? explode(',',$this->request->getGet('genders')) : [],
            'publishers' => $this->request->getGet('publishers') ? explode(',',$this->request->getGet('publishers')) : []
        ];
        $limit = $this->request->getGet('limit') ?? 50;
        return $this->response->setJSON($model->getFiltered($filters,$limit));
    }

    
    public function apiAggregate()
    {
        $metric = $this->request->getGet('metric') ?? 'count';
        $filters = [
            'genders' => $this->request->getGet('genders') ? explode(',',$this->request->getGet('genders')) : [],
            'publishers' => $this->request->getGet('publishers') ? explode(',',$this->request->getGet('publishers')) : []
        ];
        $model = new SuperheroModel();
        return $this->response->setJSON($model->aggregate($metric,$filters));
    }
}
