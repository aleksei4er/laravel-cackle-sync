<?php

namespace Aleksei4er\LaravelCackleSync;

use Aleksei4er\LaravelCackleSync\Models\CackleChannel;
use Aleksei4er\LaravelCackleSync\Models\CackleComment;
use GuzzleHttp\Client;

class LaravelCackleSync
{
    /**
     * Config array
     *
     * @var array
     */
    protected $config;

    /**
     * Constructor
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get array of channels
     *
     * @param integer $size
     * @param integer $page
     * @param string $modified
     * @return void
     */
    public function getChannels(int $size, int $page, string $modified = '')
    {
        $parameters = array_merge($this->config['parameters'], compact('size', 'page'));

        if ($modified) {
            $parameters['gtModify'] = $modified;
        }

        $url = $this->config['channel_list_url'] . "?" . http_build_query($parameters);

        $client = new Client();

        $result = $client->get($url, [
            'headers' => [
                "Content-Type" => "application/x-www-form-urlencoded; charset=utf-8",
            ],
            'http_errors' => false,
            'verify' => false,
        ]);

        return json_decode($result->getBody()->getContents());
    }

    /**
     * Get all comments of channel
     *
     * @param string $chan
     * @param integer $page
     * @return object
     */
    public function getComments(string $chan, int $page = 0): object
    {
        $parameters = array_merge($this->config['parameters'], compact('chan', 'page'));

        $url = $this->config['comment_list_url'] . "?" . http_build_query($parameters);

        $client = new Client();

        $result = $client->get($url, [
            'headers' => [
                "Content-Type" => "application/x-www-form-urlencoded; charset=utf-8",
            ],
            'http_errors' => false,
            'verify' => false,
        ]);

        return json_decode($result->getBody()->getContents());
    }

    /**
     * Get comment of channel after comment id
     *
     * @param integer $commentId
     * @param string $chan
     * @param integer $page
     * @return object
     */
    public function getCommentsAfterId(string $chan, int $commentId, int $page = 0): object
    {
        $parameters = array_merge($this->config['parameters'], compact('chan', 'commentId', 'page'));

        $url = $this->config['comment_list_url'] . "?" . http_build_query($parameters);

        $client = new Client();

        $result = $client->get($url, [
            'headers' => [
                "Content-Type" => "application/x-www-form-urlencoded; charset=utf-8",
            ],
            'http_errors' => false,
            'verify' => false,
        ]);

        return json_decode($result->getBody()->getContents());
    }

    /**
     * Get comment of channel after timestamp
     *
     * @param integer $modified
     * @param string $chan
     * @param integer $page
     * @return object
     */
    public function getCommentsAfterTimestamp(int $modified, string $chan, int $page = 0): object
    {
        $parameters = array_merge($this->config['parameters'], compact('chan', 'modified', 'page'));

        $url = $this->config['comment_list_url'] . "?" . http_build_query($parameters);

        $client = new Client();

        $result = $client->get($url, [
            'headers' => [
                "Content-Type" => "application/x-www-form-urlencoded; charset=utf-8",
            ],
            'http_errors' => false,
            'verify' => false,
        ]);

        return json_decode($result->getBody()->getContents());
    }

    /**
     * Sync channels
     *
     * @param integer $page
     * @return void
     */
    public function loadChannels(int $page = 0): void
    {
        $chans = $this->getChannels(100, $page);

        if (!isset($chans->chans)) return;

        $count1 = count($chans->chans);

        foreach ($chans->chans as $chan) {

            $channel = CackleChannel::firstOrNew(['id' => $chan->id]);
            $channel->channel = $chan->channel;
            $channel->url = $chan->url;
            $channel->title = $chan->title;
            $channel->created = $chan->created;

            if (isset($chan->modify)) $channel->modified = $chan->modify;

            $channel->save();
        }

        if ($count1 >= 100) {

            $page++;

            sleep(6);

            $this->loadChannels($page);
        }
    }

    /**
     * Sync comments
     *
     * @param integer $lastCommentId
     * @param string $channel
     * @return void
     */
    public function loadComments(string $channel, int $lastCommentId = 0): void
    {
        $comments = $this->getCommentsAfterId($channel, $lastCommentId);

        $count = count($comments->comments);

        foreach ($comments->comments as $com) {
            $author = 'Аноним';

            if (isset($com->anonym) && isset($com->anonym->name)) {
                $author = $com->anonym->name;
            }

            if (isset($com->author) && isset($com->author->name)) {
                $author = $com->author->name;
            }

            $comment = CackleComment::firstOrNew(['id' => $com->id]);
            $comment->channel_id = $com->chan->id;
            $comment->comment = $com->message;
            $comment->created = $com->created;
            $comment->modified = $com->modified;
            $comment->name = $author;
            $comment->email = $author;
            $comment->ip = $com->ip;
            $comment->status = $com->status;
            $comment->save();

            if ($count >= 100) {

                sleep(6);

                $this->loadComments($com->id, $com->chan->id);
            }
        }
    }
}
