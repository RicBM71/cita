<?php

namespace App\Models;

use App\Scopes\EmpresaActivaScope;
use Illuminate\Database\Eloquent\Model;

class Cobro extends Model
{
    protected $connection = 'mysql2';

    protected $fillable = [
        'empresa_id', 'fecha', 'area_id', 'cita_id', 'paciente_id', 'fpago_id', 'tpv_id', 'importe', 'autorizacion', 'username',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new EmpresaActivaScope);

    }

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function citas()
    {
        return $this->belongsTo(Cita::class);
    }

    public function fpago()
    {
        return ($this->belongsTo(Fpago::class));
    }

    public function paciente()
    {
        return ($this->belongsTo(Paciente::class));
    }

}
