<?php

namespace App\Jobs\P52;

use App\Jobs\Job;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Classes\Old\Collection as OldCollection;

class ChunkedPushMigrateCollection extends Job implements ShouldQueue {

    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    protected $params;

    /**
     * Create a new job instance.
     *
     * ChunkedPushMigrateCollection constructor.
     * @param $params
     */
    public function __construct($params) {
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        foreach(OldCollection::getEntities($this->params) as $oldCollection) {
            $this->dispatch(new MigrateCollection($oldCollection));
        }
    }
}
