<?php
/**
 * Created by PhpStorm.
 * User: usever
 * Date: 14.06.16
 * Time: 18:50
 */

namespace App\Classes\Old;


class Brand extends Entity {

    public static function getEntities($params = []) {
        return self::connection()->select("
        SELECT 
            d.title as name,
            b.body_value as description,
            u.field_website_url_value as url,
            n.nid as nid
        FROM node AS n
        LEFT JOIN node_field_data AS d
            ON d.nid = n.nid
        LEFT JOIN node__body as b
            ON b.entity_id = n.nid
        LEFT JOIN node__field_website_url as u
            ON b.entity_id = n.nid
        WHERE n.type = 'brand'
        GROUP BY n.nid
            ");
    }


}