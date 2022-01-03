<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Empresa extends Model
{

    // Flags:
    // 0: Empresa activa
    // 1: Admite Compras
    // 2: Admite Ventas
    // 3: Nuevas Compras
    // 4: Nuevas Ventas
    // 5: Proveedora efectivo
    // 6: Factura (deshabilitar para empresas de depósito por ejemplo)

    use HasFactory, SoftDeletes;

    protected $connection = 'mysql2';

    protected $dates = ['scan_doc'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'nombre', 'razon', 'cif', 'poblacion', 'direccion', 'cpostal', 'provincia', 'telefono1', 'telefono2', 'contacto',
        'email', 'web', 'txtpie1', 'txtpie2', 'flags', 'sigla', 'titulo', 'img_logo', 'img_fondo', 'certificado',
        'passwd_cer', 'ult_bono', 'sms_usr', 'sms_password', 'sms_api', 'sms_sender', 'sms_pais',
        'sms_zona', 'sms_am', 'sms_pm', 'ccc_ss', 'tpv', 'username', 'disco',

    ];

    public function setCifAttribute($cif)
    {
        $this->attributes['cif'] = strtoupper($cif);

    }

    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);

    }

    public function setWebAttribute($web)
    {
        $this->attributes['web'] = strtolower($web);

    }

    // establecemos la relación muchos a muchos
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function scopeFlag($query, $flag)
    {
        $flag = $flag + 1; // en mySql índice empieza en 1.
        $query->whereRaw('substring(flags,' . $flag . ',1)="1"');

    }

    public function scopeVenta($query, $flag = 2)
    {
        $flag = $flag + 1; // en mySql índice empieza en 1.
        $query->whereRaw('substring(flags,' . $flag . ',1)="1"');

    }

    public function scopeProveedora($query)
    {
        $flag = 6; // en mySql índice empieza en 1.
        return $query->whereRaw('substring(flags,' . $flag . ',1)="1"');

    }

    public static function selEmpresas()
    {

        return Empresa::select('id AS value', 'nombre AS text')
            ->flag(0)
            ->orderBy('nombre', 'asc');
        // ->get();

    }

    /**
     * Caso evaoro, normalmente es la empresa 1 la común, en el caso de eva oro no está activa
     * esto permite seleccionar todas las empresas, básicamente para mto empresa.
     *
     * @return void
     */
    public static function selAllEmpresas()
    {
        return Empresa::select('id AS value', 'nombre AS text')
            ->orderBy('nombre', 'asc')
            ->get();
    }

    public function getFlag($flag)
    {
        return $this->flags[$flag];
    }

    public static function incrementaBono()
    {

        DB::table('empresas')->where('id', session('empresa_id'))->increment('ult_bono');

        $e = Empresa::findOrfail(session('empresa_id'));

        return $e->ult_bono;

    }
}
