<?php

namespace App\Policies;

use App\Models\Notas;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class NotasPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->is_admin 
        ? $this->allow('Todos los datos listados') 
        : $this->deny('Datos por Usuario Autenticado');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Notas|array  $notas
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, $notas)
    {
        return $user->usu_ide === $notas->not_usu
        ? $this->allow('Acceso permitido', 200)
        : $this->deny('Acceso denegado', 403);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->is_admin ? $this->allow() : $this->deny('Acción no autorizada', 403);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Notas  $notas
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Notas $notas)
    {
        return $user->usu_ide === $notas->not_usu 
        ? $this->allow()
        : $this->deny('Acción no autorizada');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Notas  $notas
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Notas $notas)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Notas  $notas
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Notas $notas)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Notas  $notas
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Notas $notas)
    {
        //
    }
}
