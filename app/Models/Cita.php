<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cita extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'citas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'paciente_id',
        'medico_id',
        'recepcionista_id',
        'motivo',
        'fecha_hora',
        'estado',
        'urgente',
        'observaciones',
        'diagnostico',
        'calificacion',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_hora' => 'datetime',
        'urgente' => 'boolean',
    ];

    /**
     * Get the paciente that owns the cita.
     */
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    /**
     * Get the medico that owns the cita.
     */
    public function medico(): BelongsTo
    {
        return $this->belongsTo(Medico::class);
    }

    /**
     * Get the recepcionista that owns the cita.
     */
    public function recepcionista(): BelongsTo
    {
        return $this->belongsTo(Recepcionista::class);
    }

    /**
     * Scope a query to only include citas for a specific paciente.
     */
    public function scopeForPaciente($query, $pacienteId)
    {
        return $query->where('paciente_id', $pacienteId);
    }

    /**
     * Scope a query to only include citas for a specific medico.
     */
    public function scopeForMedico($query, $medicoId)
    {
        return $query->where('medico_id', $medicoId);
    }

    /**
     * Scope a query to only include citas with a specific estado.
     */
    public function scopeWithEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope a query to only include citas that are urgentes.
     */
    public function scopeUrgentes($query)
    {
        return $query->where('urgente', true);
    }

    /**
     * Scope a query to only include citas for today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('fecha_hora', now()->toDateString());
    }

    /**
     * Scope a query to only include citas for future dates.
     */
    public function scopeFuture($query)
    {
        return $query->where('fecha_hora', '>', now());
    }

    /**
     * Scope a query to only include citas for past dates.
     */
    public function scopePast($query)
    {
        return $query->where('fecha_hora', '<', now());
    }
}