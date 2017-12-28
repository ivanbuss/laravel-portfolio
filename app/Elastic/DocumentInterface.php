<?php
/**
 * Created by PhpStorm.
 * User: usever
 * Date: 18.08.16
 * Time: 20:42
 */

namespace App\Elastic;


interface DocumentInterface {

    /**
     * Elastic document index name
     * @return string
     */
    public static function getIndexName();


    /**
     * Elastic document type name
     * @return string
     */
    public static function getTypeName();

    /**
     * Add the document to index
     * @return mixed
     */
    public function index();

    /**
     * Add all the documents to the index.
     * @return mixed
     */
    public static function indexAll();

    /**
     * Get the body of the document.
     *
     * @throws \Exception
     * @return array ["field" => "value", ... ]
     *
     */
    public function getBody();

    /**
     * Delete document from index
     * @return mixed
     */
    public function delete();

    /**
     * Perform elastic search
     * @param array $query
     * @return mixed
     */
    public static function search(array $query);
    

}