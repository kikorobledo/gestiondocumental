<?php

namespace App\Http\Livewire;

use App\Models\File;
use App\Models\Entrie;
use Livewire\Component;
use App\Models\Tracking;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Seguimiento extends Component
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

    public $tracking_id;
    public $oficio_respuesta;
    public $fecha_respuesta;
    public $comentario;
    public $entrie_id;
    public $files = [];
    public $files_edit = [];
    public $file_id;

    protected function rules(){
        return[
            'oficio_respuesta' => 'required',
            'fecha_respuesta' => 'required',
            'comentario' => 'required',
            'entrie_id' => 'required',
            'files.*' => 'mimes:pdf'
        ];
    }

    protected $messages = [
        'oficio_respuesta.required' => 'El campo oficio de respuesta es obligatorio.',
        'fecha_respuesta.required' => 'El campo fecha de respuesta es obligatorio.',
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
        $this->reset('oficio_respuesta','fecha_respuesta','comentario','entrie_id','tracking_id','files','file_id','files_edit');
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function openModalCreate(){

        $this->resetAll();

        $this->edit = false;
        $this->modal = true;
        $this->create = true;
    }

    public function openModalEdit($tracking){

        $this->resetAll();

        $this->create = false;

        $this->tracking_id = $tracking['id'];
        $this->oficio_respuesta = $tracking['oficio_respuesta'];
        $this->fecha_respuesta = $tracking['fecha_respuesta'];
        $this->comentario = $tracking['comentario'];
        $this->entrie_id = $tracking['entrie_id'];
        $this->files_edit = $tracking['files'];

        $this->edit = true;
        $this->modal = true;

    }

    public function openModalDelete($tracking){

        $this->modalDelete = true;
        $this->tracking_id = $tracking['id'];

    }

    public function openModalDeleteFile($file){

        $this->modalDeleteFile = true;
        $this->file_id = $file;

    }

    public function closeModal(){

        $this->resetAll();

        $this->modal = false;

        $this->modalDelete = false;

        $this->modalDeleteFile = false;
    }

    public function create(){

        $this->validate();

        try {


            $tracking = Tracking::create([
                'oficio_respuesta' => $this->oficio_respuesta,
                'fecha_respuesta' => $this->fecha_respuesta,
                'comentario' => $this->comentario,
                'entrie_id' => $this->entrie_id,
                'created_by' => auth()->user()->id,
            ]);

            if(isset($this->files)){

                foreach($this->files as $file){

                    $pdf = $file->store('/', 'pdfs');

                    File::create([
                        'fileable_id' => $tracking->id,
                        'fileable_type' => 'App\Models\Tracking',
                        'url' => $pdf
                    ]);
                }

                $this->dispatchBrowserEvent('removeFiles');
            }

            $this->dispatchBrowserEvent('showMessage',['success', "El seguimiento ha sido creado con exito."]);

            $this->closeModal();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->closeModal();
        }
    }

    public function update(){



        $this->validate();

        try {

            $tracking = Tracking::findorFail($this->tracking_id);

            $tracking->update([
                'oficio_respuesta' => $this->oficio_respuesta,
                'fecha_respuesta' => $this->fecha_respuesta,
                'comentario' => $this->comentario,
                'entrie_id' => $this->entrie_id,
                'updated_by' => auth()->user()->id,
            ]);

            if(isset($this->files)){

                foreach($this->files as $file){

                    $pdf = $file->store('/', 'pdfs');

                    File::create([
                        'fileable_id' => $tracking->id,
                        'fileable_type' => 'App\Models\Tracking',
                        'url' => $pdf
                    ]);
                }

                $this->dispatchBrowserEvent('removeFiles');
            }

            $this->dispatchBrowserEvent('showMessage',['success', "El seguimiento ha sido actualizado con exito."]);

            $this->closeModal();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->closeModal();
        }

    }

    public function delete(){

        try {

            $tracking = Tracking::findorFail($this->tracking_id);

            $tracking->delete();

            $this->dispatchBrowserEvent('showMessage',['success', "El seguimiento ha sido eliminado con exito."]);

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

            $trackings = Tracking::with('entrie','createdBy', 'updatedBy', 'files')
                                ->where('created_by', auth()->user()->id)
                                ->where(function($q){
                                    return $q->where('oficio_respuesta','LIKE', '%' . $this->search . '%')
                                                ->orWhere('fecha_respuesta','LIKE', '%' . $this->search . '%')
                                                ->orWhere(function($q){
                                                    return $q->whereHas('entrie', function($q){
                                                        return $q->where('folio', 'LIKE', '%' . $this->search . '%');
                                                    });
                                                });
                                })
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->pagination);

            $entries = Entrie::where('asignacion', auth()->user()->id)->get();

        }else{

            $trackings = Tracking::with('entrie','createdBy', 'updatedBy', 'files')
                                ->where('oficio_respuesta','LIKE', '%' . $this->search . '%')
                                ->orWhere('fecha_respuesta','LIKE', '%' . $this->search . '%')
                                ->orWhere(function($q){
                                    return $q->whereHas('entrie', function($q){
                                        return $q->where('folio', 'LIKE', '%' . $this->search . '%');
                                    });
                                })
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->pagination);

            $entries = Entrie::all();
        }

        return view('livewire.seguimiento', compact('entries', 'trackings'));
    }
}
