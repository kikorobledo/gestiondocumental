<?php

namespace App\Http\Livewire;

use App\Http\Traits\ComponentesTrait;
use App\Models\User;
use App\Models\Office;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class Users extends Component
{
    use WithPagination;
    use ComponentesTrait;

    public $name;
    public $email;
    public $status;
    public $role;
    public $telefono;
    public $location_id;

    protected function rules(){
        return[
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'. $this->selected_id,
            'status' => 'required',
            'telefono' => 'required',
            'role' => 'required|integer|in:2,3,4,5',
            'location_id' => 'required'
        ];
    }

    protected $messages = [
        'name.required' => 'El campo nombre es obligatorio.',
        'role.required' => 'El campo rol es obligatorio.',
        'status.required' => 'El campo estado es obligatorio.',
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
        $this->reset('selected_id','name','email','status','role', 'telefono', 'modal', 'modalDelete', 'modalDeleteFile');
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function openModalEdit($user){

        $this->resetAll();

        $this->create = false;

        $this->selected_id = $user['id'];
        $this->role = $user['roles'][0]['id'];
        $this->name = $user['name'];
        $this->email = $user['email'];
        $this->status = $user['status'];
        $this->telefono = $user['telefono'];
        $this->location_id = $user['office_id'];

        $this->edit = true;
        $this->modal = true;
    }

    public function create(){

        $this->validate();

        try {

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'status' => $this->status,
                'telefono' => $this->telefono,
                'office_id' => $this->location_id,
                'password' => 'sistema',
                'created_by' => auth()->user()->id,
            ]);

            $user->roles()->attach($this->role);

            $this->dispatchBrowserEvent('showMessage',['success', "El usuario ha sido creado con éxito."]);

            $this->resetAll();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->resetAll();
        }
    }

    public function create2(){

        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'. $this->selected_id,
            'status' => 'required',
            'telefono' => 'required'
            ]
        );

        try {

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'status' => $this->status,
                'telefono' => $this->telefono,
                'office_id' => auth()->user()->office->id,
                'password' => 'sistema',
                'created_by' => auth()->user()->id,
            ]);

            $user->roles()->attach(3);

            $this->dispatchBrowserEvent('showMessage',['success', "El usuario ha sido creado con éxito."]);

            $this->resetAll();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->resetAll();
        }
    }

    public function update(){

        $this->validate();

        try {

            $user = User::findorFail($this->selected_id);

            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'status' => $this->status,
                'telefono' => $this->telefono,
                'office_id' => auth()->user()->roles[0]->name == 'Titular' ? auth()->user()->office->id : $this->location_id,
                'updated_by' => auth()->user()->id,
            ]);

            $user->roles()->sync($this->role);

            $this->dispatchBrowserEvent('showMessage',['success', "El usuario ha sido actualizado con éxito."]);

            $this->resetAll();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->resetAll();
        }

    }

    public function delete(){

        try {

            $user = User::findorFail($this->selected_id);

            $user->delete();

            $this->dispatchBrowserEvent('showMessage',['success', "El usuario ha sido eliminado con éxito."]);

            $this->resetAll();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->resetAll();
        }
    }

    public function render()
    {

        if(auth()->user()->roles[0]->name == 'Administrador'){

            $users = User::with('roles','createdBy','updatedBy', 'office', 'officeBelonging')
                            ->where('name', 'LIKE', '%' . $this->search . '%')
                            ->orWhere('email', 'LIKE', '%' . $this->search . '%')
                            ->orWhere('telefono', 'LIKE', '%' . $this->search . '%')
                            ->orWhere('status', 'LIKE', '%' . $this->search . '%')
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

            $offices = Office::all();

            return view('livewire.users', compact('users', 'roles', 'offices'));

        }else{

            $users = User::with('roles','createdBy','updatedBy', 'office', 'officeBelonging')
                            ->where('created_by', auth()->user()->id)
                            ->where(function($q){
                                return $q->where('name', 'LIKE', '%' . $this->search . '%')
                                            ->orWhere('email', 'LIKE', '%' . $this->search . '%')
                                            ->orWhere('telefono', 'LIKE', '%' . $this->search . '%')
                                            ->orWhere('status', 'LIKE', '%' . $this->search . '%');
                            })
                            ->orderBy($this->sort, $this->direction)
                            ->paginate($this->pagination);

            return view('livewire.users2', compact('users'));

        }


    }
}
