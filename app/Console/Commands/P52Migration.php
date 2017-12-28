<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\P52\MigrateController;

class P52Migration extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'p52:migrate {--all : Migrate all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migration from drupal database';

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
        if ($this->option('all')) {
            $controller = new MigrateController();
            $controller->migrateAll();
        }
    }
}
