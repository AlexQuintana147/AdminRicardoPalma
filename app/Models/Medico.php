<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Medico extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'medicos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'especialidad',
        'horario_inicio',
        'horario_fin',
        'foto',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'horario_inicio' => 'datetime',
        'horario_fin' => 'datetime',
    ];

    /**
     * Hash the password before saving.
     *
     * @param string $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    /**
     * Get the full URL for the profile photo.
     *
     * @return string|null
     */
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return Storage::url('public/imageMedico/' . $this->foto);
        }
        return null;
    }

    /**
     * Get the citas for the médico.
     */
    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    /**
     * Check if the médico is available at a specific time.
     *
     * @param \DateTime $dateTime
     * @return bool
     */
    public function isAvailable(\DateTime $dateTime)
    {
        $time = $dateTime->format('H:i:s');
        return $time >= $this->horario_inicio && $time <= $this->horario_fin;
    }
}