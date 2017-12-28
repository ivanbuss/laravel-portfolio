<?php
/**
 * Created by PhpStorm.
 * User: usever
 * Date: 12.06.16
 * Time: 15:51
 */

namespace App\Classes\Old;

use Illuminate\Support\Facades\DB;

abstract class Entity implements EntityInterface {

    const CONNECTION = 'p52mysql';

    protected static function connection($connection = '') {
        return DB::connection($connection ? $connection : self::CONNECTION);
    }



}