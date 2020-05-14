<?php

namespace Aleksei4er\LaravelCackleSync\Jobs;

use Aleksei4er\LaravelCackleSync\LaravelCackleSync as LaravelCackle;
use Aleksei4er\LaravelCackleSync\Facades\LaravelCackleSync;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;

class LoadComments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var integer
     */
    public $retryAfter = 30;

    /**
     * The number of times the job may be attempted.
     *
     * @var integer
     */
    public $tries = 5;

    /**
     * For pagination
     *
     * @var int
     */
    private $page;

    /**
     * Last comment id that loaded before
     *
     * @var int
     */
    private $lastCommentId;

    /**
     * Time between the requests
     *
     * @var int
     */
    private $requestInterval;

    public function __construct(int $lastCommentId = 0, int $page = 0)
    {
        $this->lastCommentId = $lastCommentId;
        $this->page = $page;
        $this->requestInterval = config('laravel-cackle-sync.request_interval');
    }

    public function handle()
    {
        $comments = LaravelCackleSync::getCommentsAfterId($this->lastCommentId, $this->page);

        $comments = $comments->comments ?? [];

        LaravelCackleSync::saveComments($comments);

        if (count($comments) >= LaravelCackle::ITEMS_ON_PAGE) {
            LoadComments::dispatch($this->lastCommentId, $this->page + 1)
            ->delay(now()->addSeconds($this->requestInterval))
            ->onQueue("cackle");
        }

    }
}
