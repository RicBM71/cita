<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Empresa;
use App\Http\Resources\PacienteResource;

class DashboardController extends Controller
{
    public function index()
    {

        $user = User::with('paciente.bonos')->findOrFail(auth()->user()->id);

        $empresa = Empresa::select('nombre', 'razon', 'cif', 'poblacion', 'direccion', 'cpostal', 'provincia', 'telefono1', 'telefono2',
            'email', 'sms_usr', 'sms_password', 'sms_api', 'sms_sender', 'sms_pais', 'sms_zona', 'sms_am', 'sms_pm')
            ->findOrFail(1);

        session(['empresa' => $empresa]);

        $paciente = PacienteResource::make($user);

        return Inertia::render('Dashboard', [
            'paciente' => $paciente,
        ]);

    }
}
