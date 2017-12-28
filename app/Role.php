<?php

namespace App;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole {

    const ADMIN = 'admin';
    const CONTRIBUTOR = 'contributor';
    
    public static function getRolesArray() {
        $array = [];
        foreach (self::get() as $role) {
            $array[$role->id] = $role->name; 
        }
        return $array;
    }


}
