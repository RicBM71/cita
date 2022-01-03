<?php

namespace App\Observers;

use Carbon\Carbon;
use App\Models\Cita;
use App\Models\Hcita;

class CitaObserver
{

    public function created(Cita $cita)
    {

        $data = $cita->toArray();

        $data['cita_id']    = $cita->id;
        $data['id']         = null;
        $data['username']   = 'web_' . auth()->user()->paciente_id;
        $data['created_at'] = Carbon::now();
        $data['accion']     = 'C';

        Hcita::create($data);

    }

    public function updating(Cita $cita)
    {

        if ($cita->estado_id == 1 || $cita->estado_id == 4) {
            $this->cancelSMS($cita);
        }

        $data = $cita->toArray();

        $data['cita_id']    = $cita->id;
        $data['id']         = null;
        $data['username']   = 'web_' . auth()->user()->paciente_id;
        $data['created_at'] = Carbon::now();
        $data['accion']     = 'U';

        Hcita::create($data);

    }

    /**
     * Handle the Cita "deleted" event.
     *
     * @param  \App\Cita  $cita
     * @return void
     */
    public function deleted(Cita $cita)
    {

        $this->cancelSMS($cita);

        $data = $cita->toArray();

        $data['cita_id']    = $cita->id;
        $data['id']         = null;
        $data['username']   = auth()->user()->username;
        $data['created_at'] = Carbon::now();
        $data['accion']     = 'D';

        Hcita::create($data);

    }

    private function cancelSMS($cita)
    {

        if ($cita->sms_id == null) {
            return;
        }

        $ids[] = [
            "id_sms" => $cita->sms_id,
        ];

        $request = [
            'api_key' => session('empresa')->sms_api,
            'ids'     => $ids,
        ];

        $request = (json_encode($request));

        $headers = array('Content-Type: application/json');

        $ch = curl_init('https://api.gateway360.com/api/3.0/sms/cancel');

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);

        $result = curl_exec($ch);

        if (curl_errno($ch) != 0) {
            return abort(411, curl_errno($ch));
            //die("curl error: ".curl_errno($ch));
        }

        $sms_response = json_decode($result);

        if ($sms_response->status == 'ok') {
            $cita->update([
                'sms_id'    => null,
                'envio_sms' => null,
            ]);
        }

    }

}
