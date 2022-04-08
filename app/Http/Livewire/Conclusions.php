<?php

namespace App\Http\Livewire;

use App\Models\File;
use App\Models\Entrie;
use Livewire\Component;
use App\Models\Conclusion;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Conclusions extends Component
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

    public $conclusions_id;
    public $comentario;
    public $entrie_id;
    public $files = [];
    public $files_edit = [];
    public $file_id;

    protected function rules(){
        return[
            'comentario' => 'required',
        ];
    }

    protected $messages = [
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
        $this->reset('comentario', 'conclusions_id','files','file_id','files_edit','entrie_id');
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function openModalCreate(){

        $this->resetAll();

        $this->edit = false;
        $this->modal = true;
        $this->create = true;
    }

    public function openModalEdit($conclusion){

        $this->resetAll();

        $this->create = false;

        $this->conclusions_id = $conclusion['id'];
        $this->comentario = $conclusion['comentario'];
        $this->entrie_id = $conclusion['entrie_id'];
        $this->files_edit = $conclusion['files'];

        $this->edit = true;
        $this->modal = true;

    }

    public function openModalDelete($conclusion){

        $this->modalDelete = true;
        $this->conclusions_id = $conclusion['id'];

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


            $conclusion = Conclusion::create([
                'comentario' => $this->comentario,
                'entrie_id' => $this->entrie_id,
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

            $this->dispatchBrowserEvent('showMessage',['success', "La conclusión ha sido creado con exito."]);

            $this->closeModal();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->closeModal();
        }
    }

    public function update(){



        $this->validate();

        try {

            $conclusion = Conclusion::findorFail($this->conclusions_id);

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

            $this->dispatchBrowserEvent('showMessage',['success', "La conclusión ha sido actualizado con exito."]);

            $this->closeModal();

        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->closeModal();
        }

    }

    public function delete(){

        try {

            $conclusion = Conclusion::findorFail($this->conclusions_id);

            $conclusion->delete();

            $this->dispatchBrowserEvent('showMessage',['success', "La conclusión ha sido eliminado con exito."]);

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

            $conclusions = Conclusion::with('entrie', 'createdBy', 'updatedBy', 'files')
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

            $entries = Entrie::where('asignacion', auth()->user()->id)->get();

        }else{

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

        }


        return view('livewire.conclusions', compact('entries', 'conclusions'));
    }
}
