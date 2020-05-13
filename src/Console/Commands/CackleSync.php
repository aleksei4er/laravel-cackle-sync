<?php

namespace Aleksei4er\LaravelCackleSync\Console\Commands;

use Aleksei4er\LaravelCackleSync\Facades\LaravelCackleSync;
use Aleksei4er\LaravelCackleSync\Models\CackleChannel;
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
        $interval = config('laravel-cackle-sync.request_interval');

        LaravelCackleSync::loadChannels();

        sleep($interval);

        $channels = CackleChannel::get();

        foreach ($channels as $channel) {

            try {
                LaravelCackleSync::loadComments($channel->channel);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }

            sleep($interval);
        }
    }
}
