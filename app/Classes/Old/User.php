<?php
/**
 * Created by PhpStorm.
 * User: usever
 * Date: 12.06.16
 * Time: 15:47
 */

namespace App\Classes\Old;

class User extends Entity {

    public static function getEntities($params = []) {
        return self::connection()->select('
            SELECT 
                u.uid as uid, 
                d.name, 
                d.pass,
                role.roles_target_id as role,
                d.mail, 
                bfi.uri AS background_uri, 
                afi.uri AS avatar_uri,
                b.field_bio_value AS bio,
                p.field_points_value AS points
            FROM users AS u
            LEFT JOIN users_field_data AS d
                ON u.uid = d.uid
            /* Roles */
            LEFT JOIN user__roles AS role
                ON u.uid = role.entity_id
            /* Backgroun image */
            LEFT JOIN (
                    SELECT uid, uri
                    FROM user__field_background_image
                    JOIN file_managed
                    ON user__field_background_image.field_background_image_target_id = file_managed.fid
                    ) AS bfi
                ON bfi.uid = u.uid
            /* User picture (avatar) */ 
            LEFT JOIN (
                    SELECT uid, uri
                    FROM user__user_picture
                    JOIN file_managed
                    ON user__user_picture.user_picture_target_id = file_managed.fid
                    ) AS afi
                ON afi.uid = u.uid
            /* User bio */ 
            LEFT JOIN user__field_bio AS b
                ON b.entity_id = u.uid
            /* User points */
            LEFT JOIN user__field_points AS p
                ON p.entity_id = u.uid
            GROUP BY u.uid
            ');
    }
}