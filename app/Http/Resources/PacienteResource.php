<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use App\Models\Area;
use App\Models\Cita;
use App\Models\Pacbono;
use App\Models\Tratamiento;
use Illuminate\Http\Resources\Json\JsonResource;

class PacienteResource extends JsonResource
{

    protected $area;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {

        $this->area = Area::findOrFail(1);

        $dias_max_online = $this->area->dias_online + ($this->area->semanas_max_online * 7);

        $area = [
            'bloqueado' => $this->area->bloqueo_citas_online,
            'demora'    => $this->area->bloqueo_minutos,
        ];

        $citas = $this->getCitas($this->resource->paciente_id);

        $data = [
            'nom_ape'        => mb_convert_case($this->resource->paciente->nom_ape, MB_CASE_TITLE, "UTF-8"),
            'nhc'            => $this->resource->paciente->id,
            'bonos'          => $this->getBonos($this->resource->paciente->bonos),
            'citas'          => $citas['citas'],
            'ultima_cita'    => $citas['ultima_cita'],
            'tratamiento_id' => $citas['tratamiento_id'],
            'tratamientos'   => $this->getTratamientos(),
            'fecha_min'      => Carbon::today()->addDays($this->area->dias_online),
            'fecha_max'      => Carbon::today()->addDays($dias_max_online),
            'area'           => $area,
        ];

        return $data;
    }

    public function getTratamientos()
    {

        return Tratamiento::selTratamientos();

    }

    public function getBonos($bonos)
    {
        if (count($bonos) == 0) {
            return false;
        }

        $collection = collect($bonos)->sortByDesc('fecha')->firstWhere('caducado', false); //->where('caducado', false);

        $fecha_caducidad = Carbon::parse($collection->fecha)->addDays($collection->caducidad)->isoFormat('M/YY');

        $citas = $this->getCitasBono($collection);

        return [
            'sesiones'        => $collection->sesiones,
            'fecha_bono'      => $collection->fecha,
            'bono'            => $collection->bono,
            'tratamiento_id'  => $collection->tratamiento_id,
            'citas'           => $citas,
            'fecha_caducidad' => $fecha_caducidad,
            'caducado'        => $collection->caducado,
            'usadas'          => count($citas),
        ];

        $activos = array();

        foreach ($collection as $bono) {

            //$dias_para_caducidad = $bono->caducidad - Carbon::now()->diffInDays(Carbon::parse($bono->fecha));
            $fecha_caducidad = Carbon::parse($bono->fecha)->addDays($bono->caducidad)->isoFormat('M/YY');

            array_push($activos, [
                'sesiones'        => $bono->sesiones,
                'fecha_bono'      => $bono->fecha,
                'bono'            => $bono->bono,
                'tratamiento_id'  => $bono->tratamiento_id,
                'citas'           => $this->getCitasBono($bono),
                'fecha_caducidad' => $fecha_caducidad,
                'caducado'        => $bono->caducado,
            ]);
        }

        return $activos;
    }

    private function getCitasBono($bono)
    {

        $collection = Cita::with('paciente')->usadasBono($bono->bono, $bono->fecha)->orderBy('fecha', 'desc')->get();

        $arr = array();
        foreach ($collection as $item) {
            array_push($arr, [
                'fecha'       => Carbon::parse($item->fecha . " " . $item->hora . ":00")->isoFormat('D MMM Y [-] H:mm'),
                'fecha_corta' => Carbon::parse($item->fecha . " " . $item->hora . ":00")->isoFormat('D[/]MM'),
                'hora'        => $item->hora,
                'importe'     => $item->importe,
                'paciente_id' => $item->paciente_id,
                'nombre'      => mb_convert_case($item->paciente->nombre, MB_CASE_TITLE, "UTF-8"),
            ]);
        }

        return $arr;

        return $collection;
    }

    private function getCitas($paciente_id)
    {

        $collection = Cita::with(['paciente', 'estado', 'tratamiento'])
            ->paciente($paciente_id)
            ->where('estado_id', '<>', 4)
            ->where('facultativo_id', '<>', 3)
            ->orderBy('fecha', 'desc')
            ->orderBy('estado_id', 'asc')
            ->get()->take(5);

        $arr                = array();
        $tratamiento_id     = false;
        $hay_cita_pendiente = false;

        foreach ($collection as $item) {

            if (!$hay_cita_pendiente && $item->estado_id == 1) {
                $hay_cita_pendiente = true;
            }
            if (count($arr) == 0 && $tratamiento_id === false) {
                $tratamiento_id = $item->tratamiento_id;
            }

            array_push($arr, [
                'id'           => $item->id,
                'notifica_web' => $item->notifica_web,
                'fecha'        => Carbon::parse($item->fecha . " " . $item->hora . ":00")->isoFormat('D MMM Y [-] H:mm'),
                'importe'      => $item->importe,
                'bono'         => Pacbono::getSesionesBono($item->paciente->id, $item->bono),
                'estado_id'    => $item->estado_id,
                'nombre'       => $item->paciente->nombre,
                'nombre_web'   => $item->tratamiento->nombre_web,
            ]);
        }

        if ($hay_cita_pendiente) {
            $cita = array_shift($arr);
            return [
                'citas'          => $arr,
                'ultima_cita'    => $cita,
                'tratamiento_id' => $tratamiento_id,
            ];
        }

        return [
            'citas'          => $arr,
            'ultima_cita'    => ['estado_id' => false],
            'tratamiento_id' => $tratamiento_id,
        ];
    }
}
