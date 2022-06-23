<?php

namespace App\Http\Traits;

trait ComponentesTrait{


    public $modal = false;
    public $modalDelete = false;
    public $modalDeleteFile = false;
    public $create = false;
    public $edit = false;
    public $search;
    public $sort = 'id';
    public $direction = 'desc';
    public $pagination=10;
    public $selected_id;

    public function order($sort){

        if($this->sort == $sort){
            if($this->direction == 'desc'){
                $this->direction = 'asc';
            }else{
                $this->direction = 'desc';
            }
        }else{
            $this->sort = $sort;
            $this->direction = 'asc';
        }
    }

    public function updatedPagination(){
        $this->resetPage();
    }

    public function updatingSearch(){
        $this->resetPage();
    }

    public function openModalDelete($model){

        $this->modalDelete = true;

        $this->selected_id = $model['id'];

    }

    public function openModalCreate(){
        $this->resetAll();
        $this->modal = true;
        $this->create =true;
    }

    public function openModalDeleteFile($file){

        $this->modalDeleteFile = true;
        $this->file_id = $file;

    }

}
