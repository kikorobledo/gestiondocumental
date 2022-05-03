<?php

namespace App\Http\Livewire;

use App\Models\File;
use App\Models\User;
use App\Models\Entrie;
use Livewire\Component;
use App\Models\Dependency;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Notifications\NotifyUserAsignation;

class Entries extends Component
{


    use WithPagination;
    use WithFileUploads;

    public $modal = false;
    public $modalDelete = false;
    public $modalDeleteFile = false;
    public $create = false;
    public $edit = false;
    public $search;
    public $sort = 'id';
    public $direction = 'desc';
    public $pagination=10;

    public $entrie_id;
    public $folio;
    public $asunto;
    public $destinatario;
    public $fecha_termino;
    public $origen;
    public $numero_oficio;
    public $asignadoA;
    public $auxUser;
    public $files = [];
    public $files_edit = [];
    public $file_id;

    protected function rules(){
        return[
            'folio' => 'required',
            'numero_oficio' => 'required',
            'asunto' => 'required',
            'destinatario' => 'required',
            'origen' => 'required',
            'asignadoA' => 'required',
            'files.*' => 'mimes:pdf',
            'fecha_termino' => 'date',
        ];
    }

    protected $messages = [
        'asignadoA.required' => 'El campo asignar a es obligatorio.',
        'fecha_termino.date' => 'El campo fecha de termino debe de ser una fecha valida.',
        'numero_oficio.required' => 'El campo número de oficio a es obligatorio.',
        'files.*.mimes' => 'Solo se admiten archivos PDF'
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
        $this->reset('entrie_id','folio', 'asunto', 'destinatario', 'origen', 'asignadoA', 'numero_oficio', 'files','file_id','files_edit', 'fecha_termino');
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function openModalCreate(){

        $this->resetAll();

        $this->edit = false;
        $this->create = true;
        $this->modal = true;
    }

    public function openModalEdit($entrie){

        $this->resetAll();

        $this->create = false;

        $this->entrie_id = $entrie['id'];
        $this->folio = $entrie['folio'];
        $this->numero_oficio = $entrie['numero_oficio'];
        $this->asunto = $entrie['asunto'];
        $this->destinatario = $entrie['destinatario_id'];
        $this->origen = $entrie['origen_id'];
        $this->asignadoA = $entrie['asignacion'];
        $this->fecha_termino = $entrie['fecha_termino'];
        $this->auxUser = $entrie['asignacion'];

        $this->files_edit = $entrie['files'];

        $this->edit = true;
        $this->modal = true;
    }

    public function openModalDelete($entrie){

        $this->modalDelete = true;
        $this->entrie_id = $entrie['id'];
    }

    public function openModalDeleteFile($file){

        $this->modalDeleteFile = true;
        $this->file_id = $file;

    }

    public function closeModal(){
        $this->resetall();
        $this->modal = false;
        $this->modalDelete = false;
        $this->modalDeleteFile = false;
    }

    public function sendWhastapp($entrie){

        $mensaje = "https://api.whatsapp.com/send?phone=+521" . $entrie->asignadoA->telefono . "&text=Se%20te%20ha%20asignado%20la%20entrada%20con%20folio%20" . $entrie->folio . ".%20Visita%20el%20portal.%0A";

        $this->dispatchBrowserEvent('sendWhatsApp',$mensaje);

    }

    public function create(){

        $this->validate();

        try {

            $entrie = Entrie::create([
                'folio' => $this->folio,
                'asunto' => $this->asunto,
                'numero_oficio' => $this->numero_oficio,
                'destinatario_id' => $this->destinatario,
                'fecha_termino' => $this->fecha_termino,
                'origen_id' => $this->origen,
                'asignacion' => $this->asignadoA,
                'created_by' => auth()->user()->id,
            ]);

            if(isset($this->files)){

                foreach($this->files as $file){

                    $pdf = $file->store('/', 'pdfs');

                    File::create([
                        'fileable_id' => $entrie->id,
                        'fileable_type' => 'App\Models\Entrie',
                        'url' => $pdf
                    ]);
                }

                $this->dispatchBrowserEvent('removeFiles');
            }

            $user = $entrie->asignadoA;

            $user->notify( new NotifyUserAsignation() );

            $this->dispatchBrowserEvent('showMessage',['success', "La entrada ha sido creado con exito."]);

            $this->sendWhastapp($entrie);

            $this->closeModal();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->closeModal();
        }
    }

    public function update(){

        $this->validate();

        try {

            $entrie = Entrie::findorFail($this->entrie_id);

            $entrie->update([
                'folio' => $this->folio,
                'asunto' => $this->asunto,
                'numero_oficio' => $this->numero_oficio,
                'fecha_termino' => $this->fecha_termino,
                'destinatario_id' => $this->destinatario,
                'origen_id' => $this->origen,
                'asignacion' => $this->asignadoA,
                'updated_by' => auth()->user()->id,
            ]);

            if(isset($this->files)){

                foreach($this->files as $file){

                    $pdf = $file->store('/', 'pdfs');

                    File::create([
                        'fileable_id' => $entrie->id,
                        'fileable_type' => 'App\Models\Entrie',
                        'url' => $pdf
                    ]);
                }

                $this->dispatchBrowserEvent('removeFiles');
            }

            if($this->auxUser != $entrie->asignado_a){

                $user = $entrie->asignadoA;

                $user->notify( new NotifyUserAsignation() );

                $this->sendWhastapp($entrie);

            }

            $this->dispatchBrowserEvent('showMessage',['success', "La entrada ha sido actualizado con exito."]);

            $this->closeModal();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->closeModal();
        }

    }

    public function delete(){

        try {

            $entrie = Entrie::findorFail($this->entrie_id);

            $entrie->delete();

            $this->dispatchBrowserEvent('showMessage',['success', "La entrada ha sido eliminado con exito."]);

            $this->closeModal();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->closeModal();
        }
    }

    public function deleteFile(){

        try {

            $file = File::findorFail($this->file_id);

            $file->delete();

            $this->dispatchBrowserEvent('showMessage',['success', "El archivo ha sido eliminado con exito."]);

            $this->closeModal();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->closeModal();
        }
    }

    public function render()
    {

        if(auth()->user()->roles[0]->name == 'Director' || auth()->user()->roles[0]->name == 'Coordinador'){

            $entries = Entrie::with('createdBy', 'updatedBy', 'origen', 'destinatario', 'asignadoA', 'trackings', 'conclusions', 'files')
                            ->where('asignacion', auth()->user()->id)
                            ->where(function($q){
                                return $q->where('folio', 'LIKE', '%' . $this->search . '%')
                                            ->orWhere('asunto','LIKE', '%' . $this->search . '%')
                                            ->orWhere(function($q){
                                                return $q->whereHas('destinatario', function($q){
                                                    return $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                });
                                            })
                                            ->orWhere(function($q){
                                                return $q->whereHas('origen', function($q){
                                                    return $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                });
                                            })
                                            ->orWhere(function($q){
                                                return $q->whereHas('asignadoA', function($q){
                                                    return $q->where('name', 'LIKE', '%' . $this->search . '%');
                                                });
                                            });
                            })
                            ->orderBy($this->sort, $this->direction)
                            ->paginate($this->pagination);

        }else{

            $entries = Entrie::with('createdBy', 'updatedBy', 'origen', 'destinatario', 'asignadoA', 'trackings', 'conclusions', 'files')
                            ->where('folio', 'LIKE', '%' . $this->search . '%')
                            ->orWhere('asunto','LIKE', '%' . $this->search . '%')
                            ->orWhere(function($q){
                                return $q->whereHas('destinatario', function($q){
                                    return $q->where('name', 'LIKE', '%' . $this->search . '%');
                                });
                            })
                            ->orWhere(function($q){
                                return $q->whereHas('origen', function($q){
                                    return $q->where('name', 'LIKE', '%' . $this->search . '%');
                                });
                            })
                            ->orWhere(function($q){
                                return $q->whereHas('asignadoA', function($q){
                                    return $q->where('name', 'LIKE', '%' . $this->search . '%');
                                });
                            })
                            ->orderBy($this->sort, $this->direction)
                            ->paginate($this->pagination);
        }

        $users = User::all();

        $dependencies = Dependency::all();

        return view('livewire.entries', compact('entries', 'users', 'dependencies'));
    }
}
