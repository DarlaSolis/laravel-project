<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\BloodType;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.patients.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bloodTypes = \App\Models\BloodType::all();
        return view('admin.patients.create', compact('bloodTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Clean phone number
        if ($request->filled('emergency_contact_phone')) {
            $request->merge([
                'emergency_contact_phone' => preg_replace('/[^0-9]/', '', $request->emergency_contact_phone),
            ]);
        }

        $request->validate([
            // User validations
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'id_number' => 'nullable|string|min:5|max:20|regex:/[A-Za-z0-9\-]+$/|unique:users',
            'phone' => 'nullable|digits_between:7,15',
            'address' => 'nullable|string|min:3|max:255',

            // Patient validations
            'allergies' => 'nullable|string|min:3|max:255',
            'chronic_conditions' => 'nullable|string|min:3|max:255',
            'surgical_history' => 'nullable|string|min:3|max:255',
            'family_history' => 'nullable|string|min:3|max:255',
            'blood_type_id' => 'nullable|exists:blood_types,id',
            'observations' => 'nullable|string|min:3|max:255',
            'emergency_contact_name' => 'nullable|string|min:3|max:255',
            'emergency_contact_phone' => 'nullable|digits:10',
            'emergency_contact_relationship' => 'nullable|string|min:3|max:255',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'id_number' => $request->id_number,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        $role = \Spatie\Permission\Models\Role::where('name', 'Paciente')->first();
        if ($role) {
            $user->assignRole($role);
        }

        $user->patient()->create([
            'blood_type_id' => $request->blood_type_id,
            'allergies' => $request->allergies,
            'chronic_conditions' => $request->chronic_conditions,
            'surgical_history' => $request->surgical_history,
            'family_history' => $request->family_history,
            'observations' => $request->observations,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'emergency_contact_relationship' => $request->emergency_contact_relationship,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Paciente creado',
            'text' => 'El paciente ha sido registrado correctamente',
        ]);

        return redirect()->route('admin.patients.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        return view('admin.patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        $bloodTypes = BloodType::all();

        // Determinar qué tab debe estar activo basado en los errores de validación
        if (session('errors')) {
            $errors = session('errors');

            // Array para guardar qué tabs tienen errores
            $tabsWithErrors = [];

            if ($errors->hasAny(['allergies', 'chronic_conditions', 'surgical_history', 'family_history'])) {
                $tabsWithErrors[] = 'antecedentes';
            }

            if ($errors->hasAny(['blood_type_id', 'observations'])) {
                $tabsWithErrors[] = 'informacion-general';
            }

            if ($errors->hasAny(['emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relationship'])) {
                $tabsWithErrors[] = 'contacto-emergencia';
            }

            // Guardar en sesión para la vista
            session()->flash('tabs_with_errors', $tabsWithErrors);

            // Activar el primer tab con errores (o el último si prefieres)
            if (!empty($tabsWithErrors)) {
                session()->flash('active_tab', $tabsWithErrors[0]); // Primer tab con errores
                // session()->flash('active_tab', end($tabsWithErrors)); // Último tab con errores
            }
        }

        return view('admin.patients.edit', compact('patient', 'bloodTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        // Limpiar el teléfono: quitar todo lo que no sea número
        if ($request->filled('emergency_contact_phone')) {
            $request->merge([
                'emergency_contact_phone' => preg_replace(
                    '/[^0-9]/',
                    '',
                    $request->emergency_contact_phone
                ),
            ]);
        }

        $data = $request->validate([
            // Antecedentes
            'allergies' => 'nullable|string|min:3|max:255',
            'chronic_conditions' => 'nullable|string|min:3|max:255',
            'surgical_history' => 'nullable|string|min:3|max:255',
            'family_history' => 'nullable|string|min:3|max:255',

            // Información general
            'blood_type_id' => 'nullable|exists:blood_types,id',
            'observations' => 'nullable|string|min:3|max:255',

            // Contacto de emergencia
            'emergency_contact_name' => 'nullable|string|min:3|max:255',
            'emergency_contact_phone' => 'nullable|digits:10',
            'emergency_contact_relationship' => 'nullable|string|min:3|max:255',
        ]);

        $patient->update($data);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Paciente actualizado',
            'text' => 'El paciente se actualizó correctamente',
        ]);

        return redirect()->route('admin.patients.edit', $patient);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        // No permitir borrar al paciente si es el ID 1 (admin protegido)
        if ($patient->user_id === 1) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No puedes eliminar este registro protegido'
            ]);
            return redirect()->route('admin.patients.index');
        }

        // Eliminar el paciente
        $user = $patient->user;
        $patient->delete();
        
        // Opcional: eliminar el usuario atado, o solo se borra el paciente
        if($user) {
            $user->delete();
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Paciente eliminado',
            'text' => 'El paciente ha sido eliminado correctamente del sistema'
        ]);

        return redirect()->route('admin.patients.index');
    }
}
