<?php

namespace Aleksei4er\LaravelCackleSync\Console\Commands;

use Aleksei4er\LaravelCackleSync\Jobs\LoadChannels;
use Aleksei4er\LaravelCackleSync\Jobs\LoadComments;
use Aleksei4er\LaravelCackleSync\Jobs\LoadReviews;
use Aleksei4er\LaravelCackleSync\Models\CackleComment;
use Aleksei4er\LaravelCackleSync\Models\CackleReview;
use Illuminate\Console\Command;

class CackleSync extends Command
{
    protected $signature = 'cackle:sync';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // LoadChannels::dispatch()->onQueue("cackle");

        // $lastCommentId = CackleComment::max('id') ?? 0;
        // LoadComments::dispatch($lastCommentId)->onQueue("cackle");

        $modified = CackleReview::max('modified') ?? 0;
        LoadReviews::dispatch($modified)->onQueue("cackle");
    }
}
