<?php

namespace App\Models;

use App\Http\Traits\ModelsTrait;
use Carbon\Carbon;
use App\Models\File;

use App\Models\User;
use App\Models\Conclusion;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entrie extends Model
{
    use HasFactory;
    use ModelsTrait;

    protected $guarded = [];

    public function origen(){
        return $this->belongsTo(Dependency::class, 'origen_id');
    }

    public function destinatario(){
        return $this->belongsTo(Office::class, 'destinatario_id');
    }

    public function asignadoA(){
        return $this->belongsToMany(User::class);
    }

    public function trackings(){
        return $this->hasMany(Tracking::class);
    }

    public function conclusions(){
        return $this->hasMany(Conclusion::class);
    }

    public function files(){
        return $this->morphMany(File::class, 'fileable');
    }

    public function getFechaTerminoAttribute(){
        return Carbon::createFromFormat('Y-m-d', $this->attributes['fecha_termino'])->format('d-m-Y');
    }

    protected function limit(): Attribute{
        return Attribute::make(
            get: fn($value) => Str::limit(strip_tags($this->asunto), 100)
        );
    }

}
