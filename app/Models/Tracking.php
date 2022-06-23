<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tracking extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function entrie(){
        return $this->belongsTo(Entrie::class, 'entrie_id');
    }

    public function files(){
        return $this->morphMany(File::class, 'fileable');
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getCreatedAtAttribute(){
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['created_at'])->format('d-m-Y H:i:s');
    }

    public function getUpdatedAtAttribute(){
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['updated_at'])->format('d-m-Y H:i:s');
    }

    protected function limit(): Attribute{
        return Attribute::make(
            get: fn($value) => Str::limit(strip_tags($this->comentario), 100)
        );
    }

    public function getFechaRespuestaAttribute(){
        return Carbon::createFromFormat('Y-m-d', $this->attributes['fecha_respuesta'])->format('d-m-Y');
    }
}
