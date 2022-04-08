<?php

namespace App\Http\Livewire;

use App\Models\Dependency;
use Livewire\Component;
use Livewire\WithPagination;

class Dependencies extends Component
{

    use WithPagination;

    public $modal = false;
    public $modalDelete = false;
    public $create = false;
    public $edit = false;
    public $search;
    public $sort = 'id';
    public $direction = 'desc';
    public $pagination=10;

    public $dependency_id;
    public $name;

    protected function rules(){
        return[
            'name' => 'required',
        ];
    }

    protected $messages = [
        'name.required' => 'El campo nombre es obligatorio.',
    ];

    public function updatingSearch(){
        $this->resetPage();
    }

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

    public function resetAll(){
        $this->reset('dependency_id','name');
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function openModalCreate(){

        $this->resetAll();

        $this->edit = false;
        $this->create = true;
        $this->modal = true;
    }

    public function openModalEdit($dependency){

        $this->resetAll();

        $this->create = false;

        $this->dependency_id = $dependency['id'];
        $this->name = $dependency['name'];

        $this->edit = true;
        $this->modal = true;
    }

    public function openModalDelete($dependency){

        $this->modalDelete = true;
        $this->dependency_id = $dependency['id'];
    }

    public function closeModal(){
        $this->resetall();
        $this->modal = false;
        $this->modalDelete = false;
    }

    public function create(){

        $this->validate();

        try {

            Dependency::create([
                'name' => $this->name,
                'created_by' => auth()->user()->id,
            ]);

            $this->dispatchBrowserEvent('showMessage',['success', "La dependencia ha sido creado con exito."]);

            $this->closeModal();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->closeModal();
        }
    }

    public function update(){

        $this->validate();

        try {

            $dependency = Dependency::findorFail($this->dependency_id);

            $dependency->update([
                'name' => $this->name,
                'updated_by' => auth()->user()->id,
            ]);

            $this->dispatchBrowserEvent('showMessage',['success', "La dependencia ha sido actualizado con exito."]);

            $this->closeModal();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->closeModal();
        }

    }

    public function delete(){

        try {

            $dependency = Dependency::findorFail($this->dependency_id);

            $dependency->delete();

            $this->dispatchBrowserEvent('showMessage',['success', "La dependencia ha sido eliminado con exito."]);

            $this->closeModal();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->closeModal();
        }
    }

    public function render()
    {

        $dependencies = Dependency::with('createdBy', 'updatedBy')
                                        ->where('name', 'LIKE', '%' . $this->search . '%')
                                        ->orderBy($this->sort, $this->direction)
                                        ->paginate($this->pagination);

        return view('livewire.dependencies', compact('dependencies'));
    }
}
