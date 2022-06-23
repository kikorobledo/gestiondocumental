<?php

namespace App\Models;

use App\Http\Traits\ModelsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dependency extends Model
{
    use HasFactory;
    use ModelsTrait;

    protected $guarded = [];

}
