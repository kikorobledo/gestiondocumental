<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Roles extends Component
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

    public $role_id;
    public $name;
    public $permissionsList = [];

    protected function rules(){
        return[
            'name' => 'required'
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
        $this->reset('name','permissionsList');
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function openModalCreate(){

        $this->resetAll();

        $this->edit = false;
        $this->modal = true;
        $this->create = true;
    }

    public function openModalEdit($role){

        $this->resetAll();

        $this->create = false;

        $this->role_id = $role['id'];
        $this->name = $role['name'];

        foreach($role['permissions'] as $permission){
            array_push($this->permissionsList, (string)$permission['id']);
        }

        $this->edit = true;
        $this->modal = true;

    }

    public function openModalDelete($role){

        $this->modalDelete = true;
        $this->role_id = $role['id'];
    }

    public function closeModal(){

        $this->resetAll();

        $this->modal = false;

        $this->modalDelete = false;
    }

    public function create(){

        $this->validate();

        try {

            $role = Role::create([
                'name' => $this->name,
                'created_by' => auth()->user()->id,
            ]);

            $role->permissions()->sync($this->permissionsList);

            $this->dispatchBrowserEvent('showMessage',['success', "El rol ha sido creado con exito."]);

            $this->closeModal();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->closeModal();
        }
    }

    public function update(){



        $this->validate();

        try {

            $role = Role::findorFail($this->role_id);

            $role->update([
                'name' => $this->name,
                'updated_by' => auth()->user()->id,
            ]);

            $role->permissions()->sync($this->permissionsList);



            $this->dispatchBrowserEvent('showMessage',['success', "El rol ha sido actualizado con exito."]);

            $this->closeModal();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->closeModal();
        }

    }

    public function delete(){

        try {

            $role = Role::findorFail($this->role_id);

            $role->delete();

            $this->dispatchBrowserEvent('showMessage',['success', "El rol ha sido eliminado con exito."]);

            $this->closeModal();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->closeModal();
        }
    }

    public function render()
    {

        $roles = Role::with('createdBy','updatedBy','permissions')
                        ->where('name', 'LIKE', '%' . $this->search . '%')
                        ->orderBy($this->sort, $this->direction)
                        ->paginate($this->pagination);

        $permissions = Permission::orderBy('area','desc')->get();

        $permissions = $permissions->groupBy(function($permission) {
            return $permission->area;
        })->all();

        return view('livewire.roles', compact('roles', 'permissions'));
    }
}
