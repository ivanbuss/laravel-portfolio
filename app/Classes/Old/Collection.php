<?php
/**
 * Created by PhpStorm.
 * User: usever
 * Date: 16.06.16
 * Time: 12:51
 */

namespace App\Classes\Old;

use DB;
use Illuminate\Database\Query\Builder;

class Collection extends Entity {

    public static function countEntities() {
        $query = self::getCollectionsQuery()->toSql();
        $result = self::connection()->select("SELECT COUNT(*) as amount FROM ({$query}) as countable");
        return is_array($result) ? (int) $result[0]->amount : $result;
    }

    private static function getCollectionsQuery() {
        return self::connection()->table(DB::raw('node'))
            ->select(DB::raw('
                node.nid as user_deck_nid,
                deck_type.deck_nid as deck_nid,
                node_field_data.uid as uid,
                node_field_data.title as name,
                node__body.body_value as description,
                node__body.body_summary as summary,
                node__field_quantity.field_quantity_value as quantity,
                node__field_rating.field_rating_value as rating,
                node__field_wishlist.field_wishlist_value as wishlist,
                node__field_trade.field_trade_value as tradelist
                
            '))
            ->leftJoin('node_field_data', 'node_field_data.nid', '=', 'node.nid')
            ->leftJoin('node__body', 'node__body.entity_id', '=', 'node.nid')
            ->leftJoin(DB::raw('
                (
                    SELECT 
                        fdt.entity_id as user_deck_nid, 
                        node.nid as deck_nid
                    FROM node__field_deck_type as fdt
                    LEFT JOIN node 
                        ON fdt.field_deck_type_target_id = node.nid
                    GROUP BY fdt.entity_id
                ) AS deck_type
            '), function($join) {
                $join->on('deck_type.user_deck_nid', '=', 'node.nid');
            })
            ->leftJoin('node__field_quantity', 'node__field_quantity.entity_id', '=', 'node.nid')
            ->leftJoin('node__field_rating', 'node__field_rating.entity_id', '=', 'node.nid')
            ->leftJoin('node__field_wishlist', 'node__field_wishlist.entity_id', '=', 'node.nid')
            ->leftJoin('node__field_trade', 'node__field_trade.entity_id', '=', 'node.nid')
            // ->where(DB::raw("node.type = 'user_deck' AND deck_type.deck_nid IS NOT NULL"))
            ->where("node.type", '=', 'user_deck')
            ->whereNotNull('deck_type.deck_nid')
            ->groupBy('user_deck_nid');
    }

    public static function getEntities($params = []) {
        $query = self::getCollectionsQuery();

        self::setOffset($query, $params);

        return $query->get();
    }

    protected static function setOffset(Builder &$query, array $params = []) {
        if (isset($params['limit']) && is_numeric($params['limit']))  {
            $query->take($params['limit']);
        }
        if (isset($params['offset']) && is_numeric($params['offset'])) {
            $query->skip($params['offset']);
        }
    }

}
