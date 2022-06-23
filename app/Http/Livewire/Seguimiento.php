<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\File;
use App\Models\Entrie;
use Livewire\Component;
use App\Models\Tracking;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Collection;
use App\Http\Traits\ComponentesTrait;
use Illuminate\Support\Facades\Storage;

class Seguimiento extends Component
{

    use WithPagination;
    use WithFileUploads;
    use ComponentesTrait;

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
            'comentario' => 'required|not_in:<p><br></p>',
            'entrie_id' => 'required',
            'files.*' => 'mimes:pdf'
        ];
    }

    protected $messages = [
        'oficio_respuesta.required' => 'El campo oficio de respuesta es obligatorio.',
        'fecha_respuesta.required' => 'El campo fecha de respuesta es obligatorio.',
        'files.*.mimes' => 'Solo se admiten archivos PDF',
        'comentario.not_in' => 'El comentario es obligatorio'
    ];

    public function updatedComentario(){

        $this->comentario = str_replace("'", "\"", $this->comentario);

        $this->dispatchBrowserEvent('quill-get');

    }

    public function resetAll(){
        $this->reset('oficio_respuesta','fecha_respuesta','comentario','entrie_id','selected_id','files','file_id','files_edit','modal','modalDelete', 'modalDeleteFile');
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function openModalEdit($tracking){

        $this->resetAll();

        $this->create = false;

        $this->selected_id = $tracking['id'];
        $this->oficio_respuesta = $tracking['oficio_respuesta'];
        $this->fecha_respuesta = Carbon::createFromFormat('d-m-Y', $tracking['fecha_respuesta'])->format('Y-m-d');
        $this->comentario = $tracking['comentario'];
        $this->entrie_id = $tracking['entrie_id'];
        $this->files_edit = $tracking['files'];

        $this->edit = true;
        $this->modal = true;

    }

    public function create(){

        $this->validate();

        try {

            $tracking = Tracking::create([
                'oficio_respuesta' => $this->oficio_respuesta,
                'fecha_respuesta' => $this->fecha_respuesta,
                'comentario' => $this->comentario,
                'entrie_id' => $this->entrie_id,
                'office_id' => auth()->user()->office ? auth()->user()->office->id : auth()->user()->officeBelonging->id,
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

            $this->dispatchBrowserEvent('showMessage',['success', "El seguimiento ha sido creado con éxito."]);

            $this->resetAll();

        } catch (\Throwable $th) {

            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->resetAll();
        }
    }

    public function update(){

        $this->validate();

        try {

            $tracking = Tracking::findorFail($this->selected_id);

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

            $this->dispatchBrowserEvent('showMessage',['success', "El seguimiento ha sido actualizado con éxito."]);

            $this->resetAll();

        } catch (\Throwable $th) {

            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->resetAll();
        }

    }

    public function delete(){

        try {

            $tracking = Tracking::findorFail($this->selected_id);

            foreach ($tracking->files as $file) {

                Storage::disk('pdfs')->delete($file->url);

                $file->delete();
            }

            $tracking->delete();

            $this->dispatchBrowserEvent('showMessage',['success', "El seguimiento ha sido eliminado con éxito."]);

            $this->resetAll();

        } catch (\Throwable $th) {

            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->resetAll();
        }
    }

    public function deleteFile(){

        try {

            $file = File::findorFail($this->file_id);

            Storage::disk('pdfs')->delete($file->url);

            $file->delete();

            $this->dispatchBrowserEvent('showMessage',['success', "El archivo ha sido eliminado con éxito."]);

            $this->resetAll();

        } catch (\Throwable $th) {

            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->resetAll();
        }
    }

    public function render()
    {

        if(auth()->user()->roles[0]->name == 'Titular'){

            $trackings = Tracking::with('entrie','createdBy', 'updatedBy', 'files')
                                ->whereHas('entrie', function($q){
                                    return $q->where('created_by', auth()->user()->id);
                                })
                                ->where(function($q){
                                    return $q->where('oficio_respuesta','LIKE', '%' . $this->search . '%')
                                                ->orWhere('fecha_respuesta','LIKE', '%' . $this->search . '%')
                                                ->orWhere('comentario','LIKE', '%' . $this->search . '%')
                                                ->orWhere(function($q){
                                                    return $q->whereHas('entrie', function($q){
                                                        return $q->where('folio', 'LIKE', '%' . $this->search . '%');
                                                    });
                                                });
                                })
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->pagination);

            $entries = Entrie::where('created_by', auth()->user()->id)->get();

        }elseif(auth()->user()->roles[0]->name == 'Administrador'){

            $trackings = Tracking::with('entrie','createdBy', 'updatedBy', 'files')
                                ->where('oficio_respuesta','LIKE', '%' . $this->search . '%')
                                ->orWhere('fecha_respuesta','LIKE', '%' . $this->search . '%')
                                ->orWhere('comentario','LIKE', '%' . $this->search . '%')
                                ->orWhere(function($q){
                                    return $q->whereHas('entrie', function($q){
                                        return $q->where('folio', 'LIKE', '%' . $this->search . '%');
                                    });
                                })
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->pagination);

            $entries = Entrie::all();

        }else{

            $entries = Entrie::with('trackings')->whereHas('asignadoA', function($q){
                                        return $q->where('users.id', '=', auth()->user()->id);
                                    })->get();


            $trackings = Tracking::with('entrie','createdBy', 'updatedBy', 'files')
                                    ->whereHas('entrie', function($q){
                                        return $q->whereHas('asignadoA', function($q){
                                            return $q->where('users.id', '=', auth()->user()->id);
                                        });
                                    })
                                    ->where('created_by', auth()->user()->id)
                                    ->where(function($q){
                                        return $q->where('oficio_respuesta','LIKE', '%' . $this->search . '%')
                                                    ->orWhere('fecha_respuesta','LIKE', '%' . $this->search . '%')
                                                    ->orWhere('comentario','LIKE', '%' . $this->search . '%')
                                                    ->orWhere(function($q){
                                                        return $q->whereHas('entrie', function($q){
                                                            return $q->where('folio', 'LIKE', '%' . $this->search . '%');
                                                        });
                                                    });
                                    })
                                    ->orderBy($this->sort, $this->direction)
                                    ->paginate($this->pagination);

        }

        return view('livewire.seguimiento', compact('entries', 'trackings'));
    }
}
