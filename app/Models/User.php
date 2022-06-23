<?php

namespace App\Models;

use App\Http\Traits\ModelsTrait;
use Carbon\Carbon;
use App\Models\Entrie;
use App\Models\Office;
use App\Models\Dependency;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\HasProfilePhoto;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasRoles;
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use ModelsTrait;

    const AREAS = [
        'Roles',
        'Permisos',
        'Usuarios',
        'Entradas',
        'Dependencias',
        'Conclusiones',
        'Oficinas'
    ];

    const UBICACIONES = [
        'Dirección',
        'Oficialia',
        'Regional',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'office_id',
        'status',
        'telefono',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function office(){
        return $this->hasOne(Office::class);
    }

    public function officeBelonging(){
        return $this->belongsTo(Office::class, 'office_id');
    }

    public function entries(){
        return $this->belongsToMany(Entrie::class);
    }

}
