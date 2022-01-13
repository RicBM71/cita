<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Area;
use App\Models\Cita;
use App\Models\Bloqueo;
use App\Models\Festivo;
use App\Models\Horario;
use Inertia\Controller;
use App\Models\Paciente;
use App\Models\Facultativo;
use App\Http\Requests\CitaStoreRequest;
use Illuminate\Support\Facades\Redirect;

class ReservasController extends Controller
{
    public function index($fecha, $turno)
    {

        $fecha = ($fecha == null) ? date('Y-m-d') : $fecha;

        $horas_libres = $this->horasLibres($fecha, $turno);

        if (request()->wantsJson()) {
            return [
                'horas_libres'   => $horas_libres['horas'],
                'facultativo_id' => $horas_libres['facultativo_id'],
                'msg'            => $horas_libres['msg'],
            ];
        }
    }

    public function store(CitaStoreRequest $request)
    {

        $area = Area::findOrFail(1);

        if ($area->bloqueo_citas_online) {
            return abort(403, 'No esta activado el servicio de citas online, contacta telefónicamente. Disculpa las molestias');
        }

        $input = $request->validated();

        $input['paciente_id']  = auth()->user()->paciente_id;
        $input['estado_id']    = 1;
        $input['tune']         = false;
        $input['notifica_web'] = true;

        $precios                    = Paciente::getPrecioTratamiento($input['paciente_id'], $input['tratamiento_id']);
        $input['importe']           = $precios['importe'];
        $input['importe_ponderado'] = $precios['importe_ponderado'];

        $input['username'] = 'web_' . $input['paciente_id'];

        Cita::create($input);

        return Redirect::back()->with('success', 'Cita creada!');

    }

    public function destroy(Cita $cita)
    {

        if ($cita->paciente->id != auth()->user()->paciente_id) {
            return abort(404, 'No se ha encontrado el registro!');
        }

        // TODO: Recalcular importe al anular y asignar bono??
        // PROBAR CancelSMS en CitaObserver

        $cita->update([
            'estado_id'    => 4,
            'bono'         => null,
            'username'     => auth()->user()->paciente_id,
            'notifica_web' => true,
        ]);

        return Redirect::back()->with('success', 'Cita cancelada!');

    }

    private function horasLibres($fecha, $turno)
    {
        $facultativo_id = auth()->user()->facultativo_id;

        if (Festivo::esFestivo($fecha) || Carbon::parse($fecha)->dayOfWeekIso >= 6) {
            return array(
                'horas'          => false,
                'facultativo_id' => false,
                'msg'            => 'Festivo-Cerrado',
            );
        }

        // si está asignado el facultitvo al paciente
        if ($facultativo_id > 0) {

            if (!Facultativo::esActivo($facultativo_id)) {
                return array(
                    'horas'          => false,
                    'facultativo_id' => false,
                    'msg'            => 'No disponible',
                );
            }

            $horas = $this->horasDisponibles($fecha, auth()->user()->facultativo_id, $this->horasOcupadas($fecha, $facultativo_id));

        } else { // si no lo está comenzamos siendo la última opción Sara.
            $facultativos = Facultativo::activos()->orderBy('id', 'desc')->get();
            foreach ($facultativos as $facultativo) {
                $facultativo_id = $facultativo->id;
                $horas          = $this->horasDisponibles($fecha, $facultativo_id, $this->horasOcupadas($fecha, $facultativo_id));
                if (count($horas) > 0) {
                    break;
                }

            }
        }

        $horas = collect($horas)->where('turno', $turno);

        return [
            'horas'          => $horas->values()->toArray(),
            'facultativo_id' => $facultativo_id,
            'msg'            => count($horas) == 0 ? 'No hay disponibilidad' : null,
        ];

    }

    private function horasOcupadas($fecha, $facultativo_id)
    {

        return Cita::select('hora', 'duracion_manual')
            ->join('tratamientos', 'tratamiento_id', '=', 'tratamientos.id')
            ->whereDate('fecha', $fecha)
            ->where('facultativo_id', $facultativo_id)
            ->where('estado_id', '<>', 4)
            ->orderBy('hora', 'asc')
            ->get();

    }

    private function horasDisponibles($fecha, $facultativo_id, $horas_ocupadas)
    {

        $area = Area::findOrFail(1);

        $bloqueo_dia_area = $area->getDiaBlockOnline(Carbon::parse($fecha)->dayOfWeek);

        $hora_apertura = Carbon::parse($fecha . $area->hora1);
        $hora_cierre   = Carbon::parse($fecha . $area->hora4);
        $frecuencia    = 30; // minutos

        // no damos la última hora. 20h -> 19:30
        $hora_cierre->subMinutes($frecuencia);

        $horas    = array();
        $bloqueos = Bloqueo::getBloqueosFisio($fecha, $facultativo_id);
        $horario  = Horario::getHorario($fecha, $facultativo_id);

        while ($hora_apertura->toTimeString() <= $hora_cierre) {

            $hora_actual = $hora_apertura->format('H:i');

            $hueco_inicio = Carbon::create($hora_apertura);
            $hueco_fin    = Carbon::create($hora_apertura)->addMinutes($frecuencia - 1);

            // Todo esto determina los huecos libres por tramo, teniendo en cuenta la duración del tratamiento.
            foreach ($horas_ocupadas as $item) {
                $hora_ocupada = Carbon::parse($fecha . ' ' . $item->hora . ':00');

                $hora_ini_sesion_ocupada = Carbon::create($hora_ocupada);
                $hora_fin_sesion_ocupada = Carbon::create($hora_ocupada)->addMinutes($item->duracion_manual - 1);

                // $k_hora_actual              = $hora_actual;
                // $k_hora_1ini_sesion_ocupada = $hora_ini_sesion_ocupada->format('Y-m-d H:i');
                // $k_hora_2fin_sesion_ocupada = $hora_fin_sesion_ocupada->format('H:i');
                // $k_hora_ocupada             = $hora_ocupada->format('Y-m-d H:i');

                // $k_hueco_1inicio = $hueco_inicio->format('Y-m-d H:i');
                // $k_hueco_2fin    = $hueco_fin->format('Y-m-d H:i');

                if ($hora_ini_sesion_ocupada->betweenIncluded($hueco_inicio, $hueco_fin) ||
                    $hora_fin_sesion_ocupada->betweenIncluded($hueco_inicio, $hueco_fin)) {
                    $hora_apertura->addMinutes($frecuencia);
                    continue 2;
                }
            }

            $hl = $hora_apertura->format('H:i:s');

            // no está dentro del horario
            if (!(Carbon::parse($hl)->betweenIncluded($horario['start_m'], $horario['end_m']) || Carbon::parse($hl)->betweenIncluded($horario['start_t'], $horario['end_t']))) {
                $hora_apertura->addMinutes($frecuencia);
                continue;
            }

            // lo hago así por si hay más de un bloqueo en el día.
            foreach ($bloqueos as $bloqueo) {

                if (Carbon::parse($hl)->betweenIncluded($bloqueo['start'], $bloqueo['end'])) {
                    $hora_apertura->addMinutes($frecuencia);
                    continue 2;
                }
            }

            $turno = $hl >= $area->tarde ? 'T' : 'M';

            $hora_bloqueada = false;
            if ($bloqueo_dia_area == 1 && $turno == 'M') {
                $hora_bloqueada = true;
            } else if ($bloqueo_dia_area == 2 && $turno == 'T') {
                $hora_bloqueada = true;
            } else if ($bloqueo_dia_area == 3) {
                $hora_bloqueada = true;
            }

            if (!$hora_bloqueada) {
                $horas[] = ['text' => $hora_actual, 'value' => $hora_actual, 'turno' => $turno];
            }

            $hora_apertura->addMinutes($frecuencia);
        }

        return $horas;

    }
}
