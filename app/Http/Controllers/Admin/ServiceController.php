<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    /**
     * Muestra el listado de servicios/clases del gimnasio y sus horarios.
     */
    public function index(): View
    {
        $services = Service::orderBy('hora_inicio', 'asc')->get();

        return view('admin.services.index', compact('services'));
    }

    /**
     * Registra un nuevo servicio o clase planificada con su horario y cupos.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre_servicio' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fin' => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'cupos_disponibles' => ['required', 'integer', 'min:1', 'max:500'],
            'estatus' => ['required', 'string', Rule::in(['Activo', 'Inactivo'])],
        ], [
            'nombre_servicio.required' => 'El nombre del servicio o clase es obligatorio.',
            'hora_inicio.required' => 'La hora de inicio es requerida.',
            'hora_fin.required' => 'La hora de fin es requerida.',
            'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'cupos_disponibles.required' => 'Debe indicar la cantidad de cupos disponibles.',
            'cupos_disponibles.min' => 'Los cupos disponibles deben ser al menos 1.',
        ], [
            'nombre_servicio' => 'nombre del servicio',
            'hora_inicio' => 'hora de inicio',
            'hora_fin' => 'hora de finalización',
            'cupos_disponibles' => 'cupos disponibles',
            'estatus' => 'estatus',
        ]);

        Service::create($validated);

        return redirect()->route('admin.services.index')->with('success', 'Servicio / Clase registrado exitosamente en la planificación.');
    }

    /**
     * Actualiza el servicio o clase seleccionada.
     */
    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $request->validate([
            'nombre_servicio' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fin' => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'cupos_disponibles' => ['required', 'integer', 'min:1', 'max:500'],
            'estatus' => ['required', 'string', Rule::in(['Activo', 'Inactivo'])],
        ], [
            'nombre_servicio.required' => 'El nombre del servicio o clase es obligatorio.',
            'hora_inicio.required' => 'La hora de inicio es requerida.',
            'hora_fin.required' => 'La hora de fin es requerida.',
            'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'cupos_disponibles.required' => 'Debe indicar la cantidad de cupos disponibles.',
            'cupos_disponibles.min' => 'Los cupos disponibles deben ser al menos 1.',
        ], [
            'nombre_servicio' => 'nombre del servicio',
            'hora_inicio' => 'hora de inicio',
            'hora_fin' => 'hora de finalización',
            'cupos_disponibles' => 'cupos disponibles',
            'estatus' => 'estatus',
        ]);

        $service->update($validated);

        return redirect()->route('admin.services.index')->with('success', 'Servicio / Clase actualizado correctamente.');
    }

    /**
     * Elimina el servicio o clase planificada.
     */
    public function destroy(Service $service): RedirectResponse
    {
        $nombre = $service->nombre_servicio;
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', "El servicio \"{$nombre}\" fue eliminado exitosamente.");
    }
}
