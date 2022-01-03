<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facultativo extends Model
{

    use HasFactory;

    protected $connection = 'mysql2';
    protected $dates      = ['fecha_nacimiento', 'fecha_baja'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'nombre', 'apellidos', 'cif', 'poblacion', 'direccion', 'cpostal', 'provincia', 'telefono1', 'telefono2', 'telefonom',
        'email', 'fecha_nacimiento', 'colegiado', 'fecha_baja', 'iban', 'color', 'alias', 'numero_ss', 'grupo_id', 'categoria_id', 'orden',
        'username', 'objetivo',

    ];

    protected $appends = ['nom_ape'];

    public function getNomApeAttribute()
    {

        return $this->nombre . " " . $this->apellidos;

    }

    public function getIbanAttribute($iban)
    {

        return getIbanPrint($iban);

    }

    public function setIbanAttribute($iban)
    {
        $iban = str_replace(" ", "", $iban);

        $this->attributes['iban'] = strtoupper($iban);

    }

    public function categoria()
    {
        return ($this->belongsTo(Categoria::class));
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }

    public function scopeActivos($query, $activo = true)
    {

        return ($activo) ? $query->where('colegiado', '>', 0)->whereNull('fecha_baja') : $query;

    }

    public function scopeGrupo($query, $grupo_id)
    {

        return $query->where('grupo_id', '=', $grupo_id);

    }

    public static function selFacultativos()
    {

        if (session('facultativo_id') > 0) {
            return Facultativo::select('id AS value', 'nombre AS text')
                ->where('id', session('facultativo_id'))
                ->whereNull('fecha_baja')
                ->orderBy('id', 'asc')
                ->get();
        } else {
            return Facultativo::select('id AS value', 'nombre AS text')
                ->where('categoria_id', 1)
                ->whereNull('fecha_baja')
                ->orderBy('id', 'asc')
                ->get();
        }

    }

    public static function esActivo($facultativo_id)
    {

        try {
            Facultativo::where('id', $facultativo_id)->whereNull('fecha_baja')->firstOrFail();
            return true;
        } catch (\Exception $th) {
            return false;
        }

    }

}
