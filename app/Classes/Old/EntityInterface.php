<?php
/**
 * Created by PhpStorm.
 * User: usever
 * Date: 14.06.16
 * Time: 21:13
 */

namespace App\Classes\Old;


interface EntityInterface {

    /**
     * Implement query getting entities from old database
     * @param $params
     * @return mixed
     */
    public static function getEntities($params);

}