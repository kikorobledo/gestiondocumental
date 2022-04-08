<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Conclusion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entrie extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function origen(){
        return $this->belongsTo(Dependency::class, 'origen_id');
    }

    public function destinatario(){
        return $this->belongsTo(Dependency::class, 'destinatario_id');
    }

    public function asignadoA(){
        return $this->belongsTo(User::class, 'asignacion');
    }

    public function trackings(){
        return $this->hasMany(Tracking::class);
    }

    public function conclusions(){
        return $this->hasMany(Conclusion::class);
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
}
