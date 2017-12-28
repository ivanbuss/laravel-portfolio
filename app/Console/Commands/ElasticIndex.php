<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Elastic;

class ElasticIndex extends Command {

    protected $elastic;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:indexAll
                             {document : The elastic document class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index';

    /**
     * Create a new command instance.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $document = "App\\Elastic\\" . $this->argument('document');
        if (class_exists($document)) {
            $document::deleteIndex();
            $document::createIndex();
            $document::indexAll();
        } else {
            echo "Wrong class name";
            return null;
        }
    }

}
