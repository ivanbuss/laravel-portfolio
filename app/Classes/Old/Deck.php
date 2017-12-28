<?php
/**
 * Created by PhpStorm.
 * User: usever
 * Date: 14.06.16
 * Time: 19:40
 */

namespace App\Classes\Old;


class Deck extends Entity {

    public static function getEntities($params = []) {
        $result = self::connection()->select('
        SELECT 
            n.nid as nid,
            d.uid as uid,
            d.title as name,
            b.body_value as description,
            bi.uri as backimage_uri,
            fi.uri as fronimage_uri,
            artist.field_artist_target_id as artist_nid,
            brand.field_brand_target_id as brand_nid,
            manufacturer.field_manufacturer_target_id as manufacturer_nid,
            printed.field_printed_value as printed,
            company.field_production_company_target_id as company_nid,
            url.field_website_url_value as url,
            year.field_year_value as year,
            gallery_image_uris.uris as gallery_uris
        FROM node AS n
        LEFT JOIN node_field_data AS d
            ON d.nid = n.nid
        LEFT JOIN node__body AS b
            ON b.entity_id = n.nid
        /* Back image */
        LEFT JOIN (
                SELECT node__field_back.entity_id, file_managed.uri
                FROM node__field_back
                JOIN file_managed
                ON node__field_back.field_back_target_id = file_managed.fid
                ) AS bi
            ON bi.entity_id = n.nid
        /* Front image */
        LEFT JOIN (
                SELECT node__field_front.entity_id, file_managed.uri
                FROM node__field_front
                JOIN file_managed
                ON node__field_front.field_front_target_id = file_managed.fid
                ) AS fi
            ON fi.entity_id = n.nid
        /* Artist */
        LEFT JOIN node__field_artist AS artist
            ON artist.entity_id = n.nid
        /* Brand */
        LEFT JOIN node__field_brand AS brand
            ON brand.entity_id = n.nid
        /* Manufacturer */
        LEFT JOIN node__field_manufacturer AS manufacturer
            ON manufacturer.entity_id = n.nid
        /* Production Company */ 
        LEFT JOIN node__field_production_company AS company
            ON company.entity_id = n.nid
        /* Printed (Number of printed decks) */  
        LEFT JOIN node__field_printed AS printed
            ON printed.entity_id = n.nid
        /* Website uri */  
        LEFT JOIN node__field_website_url AS url
            ON url.entity_id = n.nid
        /* Year */  
        LEFT JOIN node__field_year AS year
            ON year.entity_id = n.nid
        /* Year */  
        LEFT JOIN (
                SELECT node__field_gallery_images.entity_id, GROUP_CONCAT(file_managed.uri) as uris
                FROM node__field_gallery_images
                LEFT JOIN file_managed 
                    ON node__field_gallery_images.field_gallery_images_target_id = file_managed.fid
                GROUP BY node__field_gallery_images.entity_id
        
            ) AS gallery_image_uris
            ON gallery_image_uris.entity_id = n.nid
        WHERE n.type = \'deck\'
        GROUP BY n.nid
            ');

        static::explodeGalleryUri($result);

        return $result;
    }

    protected static function explodeGalleryUri(&$entities) {
        if (is_array($entities)) {
            foreach ($entities as $delta => $entity) {
                if (!is_null($entity->gallery_uris)) {
                    $entities[$delta]->gallery_uris = explode(',', $entity->gallery_uris);
                }

            }
        }
    }

}