<?php

namespace App\Models;

use App\Http\Traits\ModelsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    use ModelsTrait;

    protected $guarded = [];

    public function usuario(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function usuarios(){
        return $this->HasMany(User::class);
    }
}
