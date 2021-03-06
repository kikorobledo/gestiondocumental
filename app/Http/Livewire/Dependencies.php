<?php

namespace App\Http\Livewire;

use App\Http\Traits\ComponentesTrait;
use App\Models\Dependency;
use Livewire\Component;
use Livewire\WithPagination;

class Dependencies extends Component
{

    use WithPagination;
    use ComponentesTrait;

    public $name;

    protected function rules(){
        return[
            'name' => 'required',
        ];
    }

    protected $messages = [
        'name.required' => 'El campo nombre es obligatorio.',
    ];

    public function resetAll(){
        $this->reset('selected_id','name', 'modal','modalDelete', 'modalDeleteFile');
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function openModalEdit($dependency){

        $this->resetAll();

        $this->create = false;

        $this->selected_id = $dependency['id'];
        $this->name = $dependency['name'];

        $this->edit = true;
        $this->modal = true;
    }

    public function create(){

        $this->validate();

        try {

            Dependency::create([
                'name' => $this->name,
                'created_by' => auth()->user()->id,
            ]);

            $this->dispatchBrowserEvent('showMessage',['success', "La dependencia ha sido creada con éxito."]);

            $this->resetAll();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->resetAll();
        }
    }

    public function update(){

        $this->validate();

        try {

            $dependency = Dependency::findorFail($this->selected_id);

            $dependency->update([
                'name' => $this->name,
                'updated_by' => auth()->user()->id,
            ]);

            $this->dispatchBrowserEvent('showMessage',['success', "La dependencia ha sido actualizada con éxito."]);

            $this->resetAll();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->resetAll();
        }

    }

    public function delete(){

        try {

            $dependency = Dependency::findorFail($this->selected_id);

            $dependency->delete();

            $this->dispatchBrowserEvent('showMessage',['success', "La dependencia ha sido eliminada con éxito."]);

            $this->resetAll();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->resetAll();
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
