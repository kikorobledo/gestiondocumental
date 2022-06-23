<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\File;
use App\Models\User;
use App\Models\Entrie;
use App\Models\Office;
use Livewire\Component;
use App\Models\Dependency;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Http\Traits\ComponentesTrait;
use Illuminate\Support\Facades\Storage;

class Entries extends Component
{


    use WithPagination;
    use WithFileUploads;
    use ComponentesTrait;

    public $selected_id;
    public $asunto;
    public $destinatario;
    public $fecha_termino;
    public $origen;
    public $numero_oficio;
    public $asignadoA = [];
    public $auxUser;
    public $files = [];
    public $files_edit = [];
    public $file_id;

    protected function rules(){
        return[
            'numero_oficio' => 'required',
            'asunto' => 'required|not_in:<p><br></p>',
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
        'files.*.mimes' => 'Solo se admiten archivos PDF',
        'asunto.not_in' => 'El comentario es obligatorio'
    ];

    public function updatedAsunto(){

        $this->asunto = str_replace("'", "\"", $this->asunto);

        $this->dispatchBrowserEvent('quill-get');

    }

    public function resetAll(){
        $this->reset('selected_id', 'asunto', 'destinatario', 'origen', 'asignadoA', 'numero_oficio', 'files','file_id','files_edit', 'fecha_termino',  'modal','modalDelete', 'modalDeleteFile');
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function openModalEdit($entrie){

        $this->resetAll();

        $this->create = false;

        $this->selected_id = $entrie['id'];
        $this->numero_oficio = $entrie['numero_oficio'];
        $this->asunto = $entrie['asunto'];
        $this->destinatario = $entrie['destinatario_id'];
        $this->origen = $entrie['origen_id'];
        $this->fecha_termino = Carbon::createFromFormat('d-m-Y', $entrie['fecha_termino'])->format('Y-m-d');
        foreach($entrie['asignado_a'] as $user){
            array_push($this->asignadoA, (string)$user['id']);
        }

        $this->files_edit = $entrie['files'];

        $this->edit = true;
        $this->modal = true;
    }

    public function sendWhastapp($folio, $telefono){


        $mensaje = "https://api.whatsapp.com/send?phone=+521" . $telefono . "&text=Se%20te%20ha%20asignado%20la%20entrada%20con%20folio%20" . $folio . ".%20Visita%20el%20portal.%0A";

        $this->dispatchBrowserEvent('sendWhatsApp', $mensaje);

    }

    public function create(){


        $this->validate();

        try {

            $folio = Entrie::orderBy('folio', 'desc')->value('folio');

            $entrie = Entrie::create([
                'folio' => $folio ? $folio + 1 : 1,
                'asunto' => $this->asunto,
                'numero_oficio' => $this->numero_oficio,
                'destinatario_id' => $this->destinatario,
                'fecha_termino' => $this->fecha_termino,
                'origen_id' => $this->origen,
                'office_id' => auth()->user()->office ? auth()->user()->office->id : auth()->user()->officeBelonging->id,
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

            $entrie->asignadoA()->attach($this->asignadoA);

            $this->dispatchBrowserEvent('showMessage',['success', "La entrada ha sido creado con éxito."]);

            $this->resetAll();

        } catch (\Throwable $th) {

            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->resetAll();
        }
    }

    public function update(){

        $this->validate();

        try {

            $entrie = Entrie::findorFail($this->selected_id);

            $entrie->update([
                'asunto' => $this->asunto,
                'numero_oficio' => $this->numero_oficio,
                'fecha_termino' => $this->fecha_termino,
                'destinatario_id' => $this->destinatario,
                'origen_id' => $this->origen,
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

            $entrie->asignadoA()->sync($this->asignadoA);

            $this->dispatchBrowserEvent('showMessage',['success', "La entrada ha sido actualizado con éxito."]);

            $this->resetAll();

        } catch (\Throwable $th) {

            $this->dispatchBrowserEvent('showMessage',['error', "Lo sentimos hubo un error inténtalo de nuevo"]);

            $this->resetAll();
        }

    }

    public function delete(){

        try {

            $entrie = Entrie::findorFail($this->selected_id);

            $entrie->delete();

            $this->dispatchBrowserEvent('showMessage',['success', "La entrada ha sido eliminado con éxito."]);

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

            $entries = Entrie::with('createdBy', 'updatedBy', 'origen', 'destinatario', 'asignadoA', 'trackings', 'conclusions', 'files')
                            ->where('created_by', auth()->user()->id)
                            ->where(function($q){
                                return $q->where('folio', 'LIKE', '%' . $this->search . '%')
                                            ->orWhere('asunto','LIKE', '%' . $this->search . '%')
                                            ->orWhere('numero_oficio','LIKE', '%' . $this->search . '%')
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

            $users = User::where('created_by', auth()->user()->id)->get();

        }elseif(auth()->user()->roles[0]->name == 'Administrador'){

            $entries = Entrie::with('createdBy', 'updatedBy', 'origen', 'destinatario', 'asignadoA', 'trackings', 'conclusions', 'files')
                            ->where('folio', 'LIKE', '%' . $this->search . '%')
                            ->orWhere('numero_oficio','LIKE', '%' . $this->search . '%')
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


            $users = User::all();

        }elseif(auth()->user()->roles[0]->name == 'Oficialia de partes'){

            $entries = Entrie::with('createdBy', 'updatedBy', 'origen', 'destinatario', 'asignadoA', 'trackings', 'conclusions', 'files')
                            ->where('created_by', auth()->user()->id)
                            ->where(function($q){
                                return $q->where('folio', 'LIKE', '%' . $this->search . '%')
                                            ->orWhere('asunto','LIKE', '%' . $this->search . '%')
                                            ->orWhere('numero_oficio','LIKE', '%' . $this->search . '%')
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

            $users = User::whereHas('roles', function($q){
                                return $q->where('name', 'Titular');
                            })->get();

        }else{

            $entries = Entrie::with('createdBy', 'updatedBy', 'origen', 'destinatario', 'asignadoA', 'trackings', 'conclusions', 'files')
                                ->where(function($q){
                                    return $q->whereHas('asignadoA', function($q){
                                        return $q->where('users.id', '=', auth()->user()->id);
                                    });
                                })
                                ->where(function($q){
                                    return $q->where('folio', 'LIKE', '%' . $this->search . '%')
                                                ->orWhere('numero_oficio','LIKE', '%' . $this->search . '%')
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

            $users = [];
        }

        $dependencies = Dependency::orderBy('name')->get();

        $offices = Office::orderBy('name')->get();

        return view('livewire.entries', compact('entries', 'users', 'dependencies', 'offices'));
    }
}
