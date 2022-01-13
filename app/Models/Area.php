<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $connection = 'mysql2';

    protected $fillable = [];

    public static function selAreas()
    {

        return Area::select('id AS value', 'nombre AS text')
            ->get();

    }

    public function getDiaBlockOnline($dia)
    {
        $bloqueos = str_split($this->lock_date_online);
        return $bloqueos[$dia];
    }

}
