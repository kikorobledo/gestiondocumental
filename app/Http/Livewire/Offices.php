<?php

namespace App\Http\Livewire;

use App\Http\Traits\ComponentesTrait;
use App\Models\User;
use App\Models\Office;
use Livewire\Component;
use Livewire\WithPagination;

class Offices extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public $selected_id;
    public $name;
    public $user_id;

    protected function rules(){
        return[
            'name' => 'required',
            'user_id' => 'required'
        ];
    }

    protected $messages = [
        'name.required' => 'El campo nombre es obligatorio.',
        'user_id.required' => 'El campo usuario es obligatorio.',
    ];

    public function resetAll(){
        $this->reset('name','user_id', 'selected_id', 'modal','modalDelete');
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function openModalEdit($office){

        $this->resetAll();

        $this->create = false;

        $this->selected_id = $office['id'];
        $this->name = $office['name'];
        $this->user_id = $office['user_id'];

        $this->edit = true;
        $this->modal = true;

    }

    public function create(){

        $this->validate();

        try {

            Office::create([
                'name' => $this->name,
                'user_id' => $this->user_id,
                'created_by' => auth()->user()->id,
            ]);

            $this->dispatchBrowserEvent('showMessage',['success', "La Oficina ha sido creada con éxito."]);

            $this->resetAll();

        } catch (\Throwable $th) {

            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->resetAll();
        }
    }

    public function update(){



        $this->validate();

        try {

            $office = Office::findorFail($this->selected_id);

            $office->update([
                'name' => $this->name,
                'user_id' => $this->user_id,
                'updated_by' => auth()->user()->id,
            ]);

            $this->dispatchBrowserEvent('showMessage',['success', "La oficina ha sido actualizada con éxito."]);

            $this->resetAll();

        } catch (\Throwable $th) {

            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->resetAll();
        }

    }

    public function delete(){

        try {

            $office = Office::findorFail($this->selected_id);

            $office->delete();

            $this->dispatchBrowserEvent('showMessage',['success', "La oficina ha sido eliminada con éxito."]);

            $this->resetAll();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->resetAll();
        }
    }

    public function render()
    {

        $offices = Office::with('usuario','createdBy', 'updatedBy')
                            ->where('name', 'LIKE', '%' . $this->search . '%')
                            ->orWhereHas('usuario', function($q){
                                $q->where('name', 'LIKE', '%' . $this->search . '%');
                            })
                            ->orderBy($this->sort, $this->direction)
                            ->paginate($this->pagination);



        $users = User::all();

        return view('livewire.offices', compact('offices', 'users'))->extends('layouts.admin');
    }
}
