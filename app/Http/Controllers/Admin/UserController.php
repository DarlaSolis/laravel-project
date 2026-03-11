<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación
        $data = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'id_number' => 'required|string|min:5|max:20|regex:/[A-Za-z0-9\-]+$/|unique:users',
            'phone' => 'required|digits_between:7,15',
            'address' => 'required|string|min:3|max:255',
            'role_id' => 'required|exists:roles,id'
        ]);

        // Crear usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_number' => $request->id_number,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Asignar rol
        $role = Role::findById($request->role_id);
        $user->assignRole($role);

        // Si se crea un paciente, crear también registro en tabla patients
        if ($role->name === 'Paciente') {
            try {
                // Verificar si existe relación patient() en el modelo User
                if (method_exists($user, 'patient')) {
                    $patient = $user->patient()->create([]);

                    // Redirigir a la edición del paciente creado
                    session()->flash('swal', [
                        'icon' => 'success',
                        'title' => 'Paciente creado correctamente',
                        'text' => 'Ahora puedes completar la información médica del paciente'
                    ]);

                    // Asegúrate de que esta ruta exista en routes/web.php
                    return redirect()->route('admin.patients.edit', $patient);
                } else {
                    // Si no existe la relación, redirigir a usuarios
                    session()->flash('swal', [
                        'icon' => 'warning',
                        'title' => 'Usuario creado, pero sin relación patient',
                        'text' => 'El usuario fue creado pero no se pudo crear el registro médico porque falta la relación en el modelo User'
                    ]);

                    return redirect()->route('admin.users.index');
                }

            } catch (\Exception $e) {
                // Si hay error al crear el paciente, redirigir a usuarios
                session()->flash('swal', [
                    'icon' => 'warning',
                    'title' => 'Usuario creado, pero error en paciente',
                    'text' => 'El usuario fue creado pero hubo un error al crear el registro médico: ' . $e->getMessage()
                ]);

                return redirect()->route('admin.users.index');
            }
        }

        // Si no es paciente, redirigir a lista de usuarios
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario creado correctamente',
            'text' => 'El usuario ha sido creado correctamente'
        ]);

        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Validación
        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'id_number' => 'required|string|min:5|max:20|regex:/[A-Za-z0-9\-]+$/|unique:users,id_number,' . $user->id,
            'phone' => 'required|digits_between:7,15',
            'address' => 'required|string|min:3|max:255',
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|min:8|confirmed'
        ]);

        // Preparar datos
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'id_number' => $request->id_number,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        // Actualizar contraseña si se proporcionó
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Actualizar usuario
        $user->update($data);

        // Sincronizar rol
        $role = Role::findById($request->role_id);
        $user->syncRoles([$role]);

        // Si se cambió a paciente y no tiene registro en patients
        if ($role->name === 'Paciente' && !$user->patient) {
            if (method_exists($user, 'patient')) {
                $user->patient()->create([]);
            }
        }

        // Mensaje de éxito
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario actualizado correctamente',
            'text' => 'El usuario ha sido actualizado correctamente'
        ]);

        return redirect()->route('admin.users.edit', $user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // No permitir que el usuario logueado se borre a sí mismo
        if (auth()->id() === $user->id) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No puedes eliminarte a ti mismo'
            ]);
            return redirect()->route('admin.users.index');
        }

        // Prevenir eliminación de usuario admin principal (id 1)
        if ($user->id === 1) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No puedes eliminar el usuario administrador principal'
            ]);
            return redirect()->route('admin.users.index');
        }

        // Eliminar el usuario
        $user->delete();

        // Mensaje de éxito
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario eliminado correctamente',
            'text' => 'El usuario ha sido eliminado correctamente'
        ]);

        return redirect()->route('admin.users.index');
    }
}
