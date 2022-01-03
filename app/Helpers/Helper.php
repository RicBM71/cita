<?php

use Carbon\Carbon;
use Illuminate\Support\Str;

function getDecimal($valor, $dec=2){
    return number_format($valor,$dec, ",", ".");
}

function getCurrency($valor, $currency="€"){
    return number_format($valor,2, ",", ".")." ".$currency;
}

function getDecimalExcel($valor, $dec=2){
    return round($valor, $dec);
}

function getTelefonoFijo($t){

    return (Str::length($t) == 9) ? substr($t,0, 2).' '.substr($t,2, 3).' '.substr($t,5, 2).' '.substr($t,7, 2) : $t;

}

function getTelefonoMovil($t){

    return (Str::length($t) == 9) ? substr($t,0, 3).' '.substr($t,3, 2).' '.substr($t,5, 2).' '.substr($t,7, 2) : $t;


}

function getFecha($value)
{

    if (is_null($value)) return null;

    return Carbon::parse($value)->format('d/m/Y');

}

function getFechaHoraDia($value){
    return Carbon::parse($value)->isoFormat('dddd, D [de] MMMM[:] H:mm');
}

function getHora($value)
{

    if (is_null($value)) return null;

    return Carbon::parse($value)->isoFormat('H:mm');

}

function getEjercicio($fecha){
    return Carbon::parse($fecha)->format('Y');
}

function getIbanPrint($iban){

    $iban_print = '';

    $iban = str_split($iban,4);

    foreach ($iban as $e){
        $iban_print .= $e.' ';
    }

    return $iban_print;

}

function isRoot(){
    return auth()->user()->hasRole('root') || auth()->user()->id == 1;
}

function isAdmin(){
    return auth()->user()->hasRole('admin') || auth()->user()->hasRole('root');
}

function isSupervisor(){
    return (auth()->user()->hasRole('supervisor') || auth()->user()->hasRole('admin'));
}

function hasPermiso($permiso){
    return auth()->user()->hasPermissionTo($permiso);
}

function esPropietario($obj)
{
    return ($obj->username == auth()->user()->username && Carbon::today()->format('Y-m-d')== Carbon::parse($obj->created_at)->format('Y-m-d'))
         ? true : false;
}
/**
 * @param integer $ejercicio
 * @param integer $trimestre
 * @return array ['d','h']
 */
function trimestre($ejercicio,$trimestre){

    $m = (3 * $trimestre) - 2;

    return [
        'd' => Carbon::parse($ejercicio.'-'.$m.'-01')->startOfQuarter()->format('Y-m-d'),
        'h' => Carbon::parse($ejercicio.'-'.$m.'-01')->endOfQuarter()->format('Y-m-d')
    ];

    //h 3x1 - 2

    // echo Carbon::parse('2019-01-01')->endOfQuarter()->format('Y-m-d');
    // echo Carbon::parse('2019-04-01')->endOfQuarter();
    // echo Carbon::parse('2019-07-01')->endOfQuarter();
    // echo Carbon::parse('2019-10-01')->endOfQuarter();
}

