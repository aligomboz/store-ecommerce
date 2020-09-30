<?php
namespace App\Repositoris;

use App\Http\Interfaces\RepositryInterface;
use Illuminate\Database\Eloquent\Model;

use function GuzzleHttp\Promise\all;

class Repository implements RepositryInterface
{
    protected $model;
    public function __construct(Model $model) //ليمنع التكرار الجداول
    {
        $this->model = $model;
    }

    public function all(){
        return $this->model->all();
    }
    public function create(array $data)
    {
        return $this->model->create($data);
    }
    public function update(array $data, $id)
    {
        $record = $this->find($id);
        return $record->update($data);
    }
    public function show($id)
    {
        return $this->model->findOrFail($id);
    }
    public function delete($id){
        return $this->model->destroy($id);
    }
    //get
    public function getModel(){
        return $this->model;
    }
    //set
    public function setModel($model){
        $this->model = $model;
        return $this;
    }
    //relations
    public function with($relations){
        return $this->model->with($relations);
    }
}