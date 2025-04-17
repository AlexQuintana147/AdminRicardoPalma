<?php

namespace App\Policies;

use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Medico;
use App\Models\Recepcionista;
use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CitaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny($user): bool
    {
        // Todos los usuarios autenticados pueden ver citas
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view($user, Cita $cita): bool
    {
        // Pacientes solo pueden ver sus propias citas
        if ($user instanceof Paciente) {
            return $user->id === $cita->paciente_id;
        }
        
        // Médicos solo pueden ver citas asignadas a ellos
        if ($user instanceof Medico) {
            return $user->id === $cita->medico_id;
        }
        
        // Recepcionistas y administradores pueden ver todas las citas
        return $user instanceof Recepcionista || $user instanceof Admin;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($user): bool
    {
        // Pacientes, recepcionistas y administradores pueden crear citas
        return $user instanceof Paciente || 
               $user instanceof Recepcionista || 
               $user instanceof Admin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user, Cita $cita): bool
    {
        // Pacientes solo pueden actualizar sus propias citas pendientes
        if ($user instanceof Paciente) {
            return $user->id === $cita->paciente_id && 
                   in_array($cita->estado, ['pendiente', 'reprogramada']);
        }
        
        // Médicos no pueden actualizar citas
        if ($user instanceof Medico) {
            return false;
        }
        
        // Recepcionistas y administradores pueden actualizar cualquier cita
        return $user instanceof Recepcionista || $user instanceof Admin;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete($user, Cita $cita): bool
    {
        // Solo recepcionistas y administradores pueden eliminar citas
        return $user instanceof Recepcionista || $user instanceof Admin;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore($user, Cita $cita): bool
    {
        // Solo recepcionistas y administradores pueden restaurar citas
        return $user instanceof Recepcionista || $user instanceof Admin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete($user, Cita $cita): bool
    {
        // Solo administradores pueden eliminar permanentemente citas
        return $user instanceof Admin;
    }
    
    /**
     * Determine whether the user can confirm the cita.
     */
    public function confirmar($user, Cita $cita): bool
    {
        // Médicos pueden confirmar sus propias citas
        if ($user instanceof Medico) {
            return $user->id === $cita->medico_id;
        }
        
        // Recepcionistas y administradores pueden confirmar cualquier cita
        return $user instanceof Recepcionista || $user instanceof Admin;
    }
    
    /**
     * Determine whether the user can mark the cita as attended.
     */
    public function asistir($user, Cita $cita): bool
    {
        // Médicos pueden marcar como asistidas sus propias citas
        if ($user instanceof Medico) {
            return $user->id === $cita->medico_id;
        }
        
        // Recepcionistas y administradores pueden marcar cualquier cita como asistida
        return $user instanceof Recepcionista || $user instanceof Admin;
    }
    
    /**
     * Determine whether the user can cancel the cita.
     */
    public function cancelar($user, Cita $cita): bool
    {
        // Pacientes pueden cancelar sus propias citas pendientes o reprogramadas
        if ($user instanceof Paciente) {
            return $user->id === $cita->paciente_id && 
                   in_array($cita->estado, ['pendiente', 'reprogramada', 'confirmada']);
        }
        
        // Médicos pueden cancelar sus propias citas
        if ($user instanceof Medico) {
            return $user->id === $cita->medico_id;
        }
        
        // Recepcionistas y administradores pueden cancelar cualquier cita
        return $user instanceof Recepcionista || $user instanceof Admin;
    }
    
    /**
     * Determine whether the user can reschedule the cita.
     */
    public function reprogramar($user, Cita $cita): bool
    {
        // Pacientes pueden reprogramar sus propias citas pendientes
        if ($user instanceof Paciente) {
            return $user->id === $cita->paciente_id && 
                   in_array($cita->estado, ['pendiente', 'confirmada']);
        }
        
        // Médicos pueden reprogramar sus propias citas
        if ($user instanceof Medico) {
            return $user->id === $cita->medico_id;
        }
        
        // Recepcionistas y administradores pueden reprogramar cualquier cita
        return $user instanceof Recepcionista || $user instanceof Admin;
    }
}