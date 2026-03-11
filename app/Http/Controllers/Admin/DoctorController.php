<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;


class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with(['user', 'specialty'])->get();
        return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        $specialties = Specialty::all();
        return view('admin.doctors.create', compact('specialties'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            // Datos del usuario
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'id_number' => 'nullable|string|max:20|unique:users,id_number',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',

            // Datos del doctor
            'specialty_id' => 'required|exists:specialties,id',
            'license_number' => 'nullable|string|max:50',
            'biography' => 'nullable|string|max:1000',
        ]);

        try {
            // Crear el usuario
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'id_number' => $data['id_number'] ?? null,
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
            ]);

            // Verificar que el usuario se creó
            if (!$user) {
                throw new \Exception('No se pudo crear el usuario');
            }

            // Asignar rol de Doctor
            $doctorRole = Role::where('name', 'Doctor')->first();
            if ($doctorRole) {
                $user->roles()->sync([$doctorRole->id]);
            } else {
                throw new \Exception('No se encontró el rol "Doctor"');
            }

            // Crear el doctor asociado al usuario
            $doctor = Doctor::create([
                'user_id' => $user->id,
                'specialty_id' => $data['specialty_id'],
                'license_number' => $data['license_number'],
                'biography' => $data['biography'],
            ]);

            if (!$doctor) {
                throw new \Exception('No se pudo crear el doctor');
            }

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Doctor creado!',
                'text' => "El doctor {$user->name} se ha registrado correctamente."
            ]);

            return redirect()->route('admin.doctors.index');

        } catch (\Exception $e) {
            // Si hay error, mostrar mensaje detallado
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Error: ' . $e->getMessage()
            ]);

            return redirect()->back()->withInput();
        }
    }

    public function show(Doctor $doctor)
    {
        return view('admin.doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        $specialties = Specialty::all();
        return view('admin.doctors.edit', compact('doctor', 'specialties'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $data = $request->validate([
            'specialty_id' => 'required|exists:specialties,id',
            'license_number' => 'required|string|max:50',
            'biography' => 'nullable|string|max:1000',
        ]);

        $doctor->update($data);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Doctor actualizado!',
            'text' => 'La información se actualizó correctamente.'
        ]);

        return redirect()->route('admin.doctors.index');
    }

    public function destroy(Doctor $doctor)
    {
        // Evitar eliminar al admin (usuario ID 1)
        if ($doctor->user->id === 1) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'No permitido',
                'text' => 'No puedes eliminar al doctor administrador.'
            ]);
            return redirect()->route('admin.doctors.index');
        }

        try {
            $doctorName = $doctor->user->name;
            $doctor->delete();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Eliminado!',
                'text' => "El doctor {$doctorName} ha sido eliminado correctamente."
            ]);

        } catch (\Exception $e) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo eliminar el doctor: ' . $e->getMessage()
            ]);
        }

        return redirect()->route('admin.doctors.index');
    }
}
