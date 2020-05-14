<?php

namespace Aleksei4er\LaravelCackleSync\Jobs;

use Aleksei4er\LaravelCackleSync\LaravelCackleSync as LaravelCackle;
use Aleksei4er\LaravelCackleSync\Facades\LaravelCackleSync;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;

class LoadReviews implements ShouldQueue
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
     * Time between the requests
     *
     * @var int
     */
    private $requestInterval;

    /**
     * Last timestamp
     *
     * @var int
     */
    private $modified;

    public function __construct(int $modified = 0, int $page = 0)
    {
        $this->modified = $modified;
        $this->page = $page;
        $this->requestInterval = config('laravel-cackle-sync.request_interval');
    }

    public function handle()
    {
        $reviews = LaravelCackleSync::getReviewsAfterTimestamp($this->modified, $this->page);

        $reviews = $reviews->reviews ?? [];

        LaravelCackleSync::saveReviews($reviews);

        if (count($reviews) >= LaravelCackle::ITEMS_ON_PAGE) {
            LoadReviews::dispatch($this->modified, $this->page + 1)
            ->delay(now()->addSeconds($this->requestInterval))
            ->onQueue("cackle");
        }

    }
}
