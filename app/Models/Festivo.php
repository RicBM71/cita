<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Festivo extends Model
{
    protected $connection = 'mysql2';
    protected $fillable   = [
        'fecha', 'username',
    ];

    public static function esFestivo($fecha)
    {

        try {
            Festivo::where('fecha', $fecha)->firstOrFail();
            return true;
        } catch (\Exception $e) {
            return false;
        }

    }
}
