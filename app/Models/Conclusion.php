<?php

namespace App\Models;

use App\Models\File;
use App\Models\Entrie;
use Illuminate\Support\Str;
use App\Http\Traits\ModelsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conclusion extends Model
{
    use HasFactory;
    use ModelsTrait;

    protected $guarded = [];

    public function entrie(){
        return $this->belongsTo(Entrie::class, 'entrie_id');
    }

    public function files(){
        return $this->morphMany(File::class, 'fileable');
    }

    protected function limit(): Attribute{
        return Attribute::make(
            get: fn($value) => Str::limit(strip_tags($this->comentario), 100)
        );
    }

}
