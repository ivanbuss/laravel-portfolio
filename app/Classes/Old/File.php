<?php
/**
 * Created by PhpStorm.
 * User: usever
 * Date: 13.06.16
 * Time: 10:15
 */

namespace App\Classes\Old;

use Illuminate\Support\Facades\Config;

class File extends Entity {

    public static function getEntities($params = []) {
        return self::connection()->select("
        SELECT *
        FROM file_managed AS f
        GROUP BY f.fid");
    }

    /**
     * @param $uri
     * @return string
     */
    public static function getRealPath($uri) {
        $scheme = self::fileUriScheme($uri);
        $wrappers = Config::get('p52.filesystem');
        if ($scheme && isset($wrappers[$scheme])) {
            $path =  preg_replace("~^{$scheme}\:\/\/~", $wrappers[$scheme], $uri);
            return $path;
        }

        return false;
    }

    public static function fileUriScheme($uri) {
        $position = strpos($uri, '://');
        return $position ? substr($uri, 0, $position) : FALSE;
    }
}