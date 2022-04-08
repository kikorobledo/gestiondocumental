<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

class Permissions extends Component
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

    public $permission_id;
    public $name;
    public $area;

    protected function rules(){
        return[
            'name' => 'required',
            'area' => 'required'
        ];
    }

    protected $messages = [
        'name.required' => 'El campo nombre es obligatorio.',
        'area.required' => 'El campo área es obligatorio.',
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
        $this->reset('permission_id','name','area');
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function openModalCreate(){

        $this->resetAll();

        $this->edit = false;
        $this->modal = true;
        $this->create = true;
    }

    public function openModalEdit($permission){

        $this->resetErrorBag();
        $this->resetValidation();

        $this->create = false;

        $this->permission_id = $permission['id'];
        $this->name = $permission['name'];
        $this->area = $permission['area'];

        $this->modal = true;
        $this->edit = true;
    }

    public function openModalDelete($permission){

        $this->modalDelete = true;
        $this->permission_id = $permission['id'];
    }

    public function closeModal(){
        $this->resetAll();
        $this->modal = false;
        $this->modalDelete = false;
    }

    public function create(){

        $this->validate();

        try {

            Permission::create([
                'name' => $this->name,
                'area' => $this->area,
                'created_by' => auth()->user()->id,
            ]);

            $this->dispatchBrowserEvent('showMessage',['success', "El permiso ha sido creado con exito."]);

            $this->closeModal();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->closeModal();
        }
    }

    public function update(){

        $this->validate();

        try {

            $permission = Permission::findorFail($this->permission_id);

            $permission->update([
                'name' => $this->name,
                'area' => $this->area,
                'updated_by' => auth()->user()->id,
            ]);

            $this->dispatchBrowserEvent('showMessage',['success', "El permiso ha sido actualizado con exito."]);

            $this->closeModal();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->closeModal();
        }
    }

    public function delete(){

        try {

            $permission = Permission::findorFail($this->permission_id);

            $permission->delete();

            $this->dispatchBrowserEvent('showMessage',['success', "El permiso ha sido eliminado con exito."]);

            $this->closeModal();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->closeModal();
        }
    }

    public function render()
    {
        $permissions = Permission::with('createdBy', 'updatedBy')
                                    ->where('name', 'LIKE', '%' . $this->search . '%')
                                    ->orWhere('area', 'LIKE', '%' . $this->search . '%')
                                    ->orderBy($this->sort, $this->direction)
                                    ->paginate($this->pagination);

        return view('livewire.permissions', compact('permissions'));
    }
}
