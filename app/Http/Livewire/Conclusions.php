<?php

namespace App\Http\Livewire;


use App\Models\File;
use App\Models\Entrie;
use Livewire\Component;
use App\Models\Conclusion;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Http\Traits\ComponentesTrait;
use Illuminate\Support\Facades\Storage;

class Conclusions extends Component
{

    use WithPagination;
    use WithFileUploads;
    use ComponentesTrait;

    public $comentario;
    public $entrie_id;
    public $files = [];
    public $files_edit = [];
    public $file_id;

    protected function rules(){
        return[
            'comentario' => 'required|not_in:<p><br></p>',
            'entrie_id' => 'required'
        ];
    }

    protected $messages = [
        'files.*.mimes' => 'Solo se admiten archivos PDF',
        'entrie_id.required' => 'La entrada es obligatoria',
        'comentario.not_in' => 'El comentario es obligatorio'
    ];

    public function updatedComentario(){

        $this->comentario = str_replace("'", "\"", $this->comentario);

        $this->dispatchBrowserEvent('quill-get');

    }

    public function resetAll(){
        $this->reset('comentario', 'selected_id','files','file_id','files_edit','entrie_id', 'modal','modalDelete', 'modalDeleteFile');
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function openModalEdit($conclusion){

        $this->resetAll();

        $this->create = false;

        $this->selected_id = $conclusion['id'];
        $this->comentario = $conclusion['comentario'];
        $this->entrie_id = $conclusion['entrie_id'];
        $this->files_edit = $conclusion['files'];

        $this->edit = true;
        $this->modal = true;

    }

    public function create(){

        $this->validate();

        try {


            $conclusion = Conclusion::create([
                'comentario' => $this->comentario,
                'entrie_id' => $this->entrie_id,
                'office_id' => auth()->user()->office ? auth()->user()->office->id : auth()->user()->officeBelonging->id,
                'created_by' => auth()->user()->id,
            ]);

            if(isset($this->files)){

                foreach($this->files as $file){

                    $pdf = $file->store('/', 'pdfs');

                    File::create([
                        'fileable_id' => $conclusion->id,
                        'fileable_type' => 'App\Models\Conclusion',
                        'url' => $pdf
                    ]);
                }

                $this->dispatchBrowserEvent('removeFiles');
            }

            $this->dispatchBrowserEvent('showMessage',['success', "La conclusión ha sido creado con éxito."]);

            $this->resetAll();

        } catch (\Throwable $th) {

            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->resetAll();
        }
    }

    public function update(){

        $this->validate();

        try {

            $conclusion = Conclusion::findorFail($this->selected_id);

            $conclusion->update([
                'comentario' => $this->comentario,
                'entrie_id' => $this->entrie_id,
                'updated_by' => auth()->user()->id,
            ]);

            if(isset($this->files)){

                foreach($this->files as $file){

                    $pdf = $file->store('/', 'pdfs');

                    File::create([
                        'fileable_id' => $conclusion->id,
                        'fileable_type' => 'App\Models\Conclusion',
                        'url' => $pdf
                    ]);
                }

                $this->dispatchBrowserEvent('removeFiles');
            }

            $this->dispatchBrowserEvent('showMessage',['success', "La conclusión ha sido actualizado con éxito."]);

            $this->resetAll();

        } catch (\Throwable $th) {

            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->resetAll();
        }

    }

    public function delete(){

        try {

            $conclusion = Conclusion::findorFail($this->selected_id);

            foreach ($conclusion->files as $file) {

                Storage::disk('pdfs')->delete($file->url);

                $file->delete();
            }

            $conclusion->delete();

            $this->dispatchBrowserEvent('showMessage',['success', "La conclusión ha sido eliminado con exito."]);

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

            $this->dispatchBrowserEvent('showMessage',['success', "El archivo ha sido eliminado con exito."]);

            $this->resetAll();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->resetAll();
        }
    }

    public function render()
    {

        if(auth()->user()->roles[0]->name == 'Titular'){

            $conclusions = Conclusion::with('entrie', 'createdBy', 'updatedBy', 'files')
                                    ->whereHas('entrie', function($q){
                                        return $q->where('created_by', auth()->user()->id);
                                    })
                                    ->where(function($q){
                                        return $q->where('comentario','LIKE', '%' . $this->search . '%')
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

            $conclusions = Conclusion::with('entrie', 'createdBy', 'updatedBy', 'files')
                                    ->where('comentario','LIKE', '%' . $this->search . '%')
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

            $conclusions = Conclusion::with('entrie','createdBy', 'updatedBy', 'files')
                        ->whereHas('entrie', function($q){
                            return $q->whereHas('asignadoA', function($q){
                                return $q->where('users.id', '=', auth()->user()->id);
                            });
                        })
                        ->where('created_by', auth()->user()->id)
                        ->where(function($q){
                            return $q->where('comentario','LIKE', '%' . $this->search . '%')
                                        ->orWhere(function($q){
                                            return $q->whereHas('entrie', function($q){
                                                return $q->where('folio', 'LIKE', '%' . $this->search . '%');
                                            });
                                        });
                        })
                        ->orderBy($this->sort, $this->direction)
                        ->paginate($this->pagination);

        }


        return view('livewire.conclusions', compact('entries', 'conclusions'));
    }
}
