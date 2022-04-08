<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Dependency;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class Users extends Component
{
    use WithPagination;

    public $modal = false;
    public $modalDelete = false;
    public $create = false;
    public $edit = false;
    public $search;
    public $sort = 'id';
    public $direction = 'desc';
    public $pagination = 10;

    public $user_id;
    public $name;
    public $email;
    public $status;
    public $role;
    public $telefono;
    public $location_id;

    protected function rules(){
        return[
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'. $this->user_id,
            'status' => 'required',
            'telefono' => 'required',
            'role' => 'required|integer|in:2,3,4,5',
            'location_id' => 'required'
        ];
    }

    protected $messages = [
        'name.required' => 'El campo nombre es obligatorio.',
        'role.required' => 'El campo rol es obligatorio.',
        'status.required' => 'El campo rol es obligatorio.',
        'location_id.required' => 'El campo ubicación es obligatorio.',
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
        $this->reset('user_id','name','email','status','role', 'telefono');
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function openModalCreate(){

        $this->resetAll();

        $this->edit = false;
        $this->create = true;
        $this->modal = true;
    }

    public function openModalEdit($user){

        $this->resetAll();

        $this->create = false;

        $this->user_id = $user['id'];
        $this->role = $user['roles'][0]['id'];
        $this->name = $user['name'];
        $this->email = $user['email'];
        $this->status = $user['status'];
        $this->telefono = $user['telefono'];
        $this->location_id = $user['location_id'];

        $this->edit = true;
        $this->modal = true;
    }

    public function openModalDelete($user){

        $this->modalDelete = true;
        $this->user_id = $user['id'];
    }

    public function closeModal(){
        $this->resetall();
        $this->modal = false;
        $this->modalDelete = false;
    }

    public function create(){

        $this->validate();

        try {

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'status' => $this->status,
                'telefono' => $this->telefono,
                'location_id' => $this->location_id,
                'password' => 'sistema',
                'created_by' => auth()->user()->id,
            ]);

            $user->roles()->attach($this->role);

            $this->dispatchBrowserEvent('showMessage',['success', "El usuario ha sido creado con exito."]);

            $this->closeModal();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->closeModal();
        }
    }

    public function update(){

        $this->validate();

        try {

            $user = User::findorFail($this->user_id);

            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'status' => $this->status,
                'telefono' => $this->telefono,
                'location_id' => $this->location_id,
                'updated_by' => auth()->user()->id,
            ]);

            $user->roles()->sync($this->role);

            $this->dispatchBrowserEvent('showMessage',['success', "El usuario ha sido actualizado con exito."]);

            $this->closeModal();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->closeModal();
        }

    }

    public function delete(){

        try {

            $user = User::findorFail($this->user_id);

            $user->delete();

            $this->dispatchBrowserEvent('showMessage',['success', "El usuario ha sido eliminado con exito."]);

            $this->closeModal();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->closeModal();
        }
    }

    public function render()
    {

        $users = User::with('roles','createdBy','updatedBy', 'location')
                        ->where('name', 'LIKE', '%' . $this->search . '%')
                        ->orWhere('email', 'LIKE', '%' . $this->search . '%')
                        ->orWhere('telefono', 'LIKE', '%' . $this->search . '%')
                        ->orWhere('status', 'LIKE', '%' . $this->search . '%')
                        ->orWhere(function($q){
                            return $q->whereHas('location', function($q){
                                return $q->where('name', 'LIKE', '%' . $this->search . '%');
                            });
                        })
                        ->orWhere(function($q){
                            return $q->whereHas('roles', function($q){
                                return $q->where('name', 'LIKE', '%' . $this->search . '%');
                            });
                        })
                        ->when($this->sort != 'role', function($q){
                            $q->orderBy($this->sort, $this->direction);
                        })
                        ->orderBy($this->sort, $this->direction)
                        ->paginate($this->pagination);

        $roles = Role::where('id', '!=', 1)->orderBy('name')->get();

        $dependencies = Dependency::all();;

        return view('livewire.users', compact('users', 'roles', 'dependencies'));
    }
}
