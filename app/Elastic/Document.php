<?php
/**
 * Created by PhpStorm.
 * User: usever
 * Date: 18.08.16
 * Time: 20:48
 */

namespace App\Elastic;
use App\Services\Elastic;
use Illuminate\Database\Eloquent\Model;

abstract class Document implements DocumentInterface {

    protected $elastic;

    protected $model;

    protected static $indexSettings;

    protected static $mappingProperties;

    public function __construct(Model $model) {
        $this->model = $model;
        $this->elastic = app(Elastic::class);
    }

    public function index() {
        return $this->elastic->index([
            "index" => $this->getIndexName(),
            "type" => $this->getTypeName(),
            "id" => $this->model->id,
            "body" => $this->getBody(),
        ]);
    }

    public static function prepareBulkIndex() {
        return [];
    }

    public static function indexAll() {
        $elastic = app(Elastic::class);
        $params = static::prepareBulkIndex();
        return $elastic->bulk($params);
    }

    /**
     * Get the body of the document.
     *
     * @throws \Exception
     * @return array ["field" => "value", ... ]
     *
     */
    public function getBody() {
        throw new \Exception("You must implement 'getBody' method");
    }


    /**
     * Delete document from index
     * @return mixed
     */
    public function delete() {
        return $this->elastic->delete([
            "index" => $this->getIndexName(),
            "type" => $this->getTypeName(),
            "id" => $this->model->id,
        ]);
    }

    /**
     * Perform elastic search.
     * @param array $params
     * @return mixed
     */
    public static function search(array $body) {
        $elastic = app(Elastic::class);
        $params = [
            'index' => static::getIndexName(),
            'type' => static::getTypeName(),
            'body' => $body,
        ];
        return $elastic->search($params);
    }

    /**
     * Crate index with mapping
     */
    public static function createIndex() {
        $params = [
            'index' => static::getIndexName(),
            'body' => [
                'settings' => [
                    'number_of_shards' => 1,
                    'number_of_replicas' => 0,
                    'analysis' => static::$indexSettings,
                ],
                'mappings' => [
                    static::getTypeName() => [
                        'properties' => static::$mappingProperties,
                    ],
                ]
            ]
        ];
        $elastic = app(Elastic::class);
        $elastic->createIndex($params);
    }

    public static function deleteIndex() {
        $elastic = app(Elastic::class);
        return $elastic->deleteIndex(static::getIndexName());
    }
    


}