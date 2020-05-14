<?php

namespace Aleksei4er\LaravelCackleSync;

use Aleksei4er\LaravelCackleSync\Models\CackleChannel;
use Aleksei4er\LaravelCackleSync\Models\CackleComment;
use Aleksei4er\LaravelCackleSync\Models\CackleReview;
use GuzzleHttp\Client;

class LaravelCackleSync
{
    const ITEMS_ON_PAGE = 100;
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
     * Get channels
     *
     * @param integer $page
     * @param string $modified
     * @return void
     */
    public function getChannels(int $page, string $modified = '')
    {
        $size = static::ITEMS_ON_PAGE;

        $parameters = array_merge($this->config['parameters'], compact('size', 'page'));

        if ($modified) {
            $parameters['gtModify'] = $modified;
        }

        $url = $this->config['channel_list_url'] . "?" . http_build_query($parameters);

        return $this->executeRequest($url);
    }

    /**
     * Get comments of channel
     *
     * @param integer $page
     * @return object
     */
    public function getComments(int $page = 0): object
    {
        $size = static::ITEMS_ON_PAGE;

        $parameters = array_merge($this->config['parameters'], compact('size', 'page'));

        $url = $this->config['comment_list_url'] . "?" . http_build_query($parameters);

        return $this->executeRequest($url);
    }

    /**
     * Get comments after comment id
     *
     * @param integer $commentId
     * @param integer $page
     * @return object
     */
    public function getCommentsAfterId(int $commentId, int $page = 0): object
    {
        $size = static::ITEMS_ON_PAGE;

        $parameters = array_merge($this->config['parameters'], compact('size', 'commentId', 'page'));

        $url = $this->config['comment_list_url'] . "?" . http_build_query($parameters);

        return $this->executeRequest($url);
    }

    /**
     * Get comments after timestamp
     *
     * @param integer $modified
     * @param integer $page
     * @return object
     */
    public function getCommentsAfterTimestamp(int $modified, int $page = 0): object
    {
        $size = static::ITEMS_ON_PAGE;

        $parameters = array_merge($this->config['parameters'], compact('size', 'modified', 'page'));

        $url = $this->config['comment_list_url'] . "?" . http_build_query($parameters);

        return $this->executeRequest($url);
    }

    /**
     * Get reviews after timestamp
     *
     * @param integer $modified
     * @param integer $page
     * @return object
     */
    public function getReviewsAfterTimestamp(int $modified = 0, int $page = 0): object
    {
        $size = static::ITEMS_ON_PAGE;

        $parameters = array_merge($this->config['parameters'], compact('size', 'modified', 'page'));

        $url = $this->config['review_list_url'] . "?" . http_build_query($parameters);

        return $this->executeRequest($url);
    }

    private function executeRequest($url)
    {
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
     * Save array of channels
     *
     * @param array $channels
     * @return void
     */
    public function saveChannels(array $channels): void
    {
        foreach ($channels as $c) {

            $channel = CackleChannel::firstOrNew(['id' => $c->id]);
            $channel->channel = $c->channel;
            $channel->url = $c->url;
            $channel->title = $c->title;
            $channel->created = $c->created;
            $channel->modified = $c->modify ?? null;
            $channel->save();
        }
    }

    /**
     * Save array of comments
     *
     * @param array $comments
     * @return void
     */
    public function saveComments(array $comments): void
    {
        foreach ($comments as $c) {
            $comment = CackleComment::firstOrNew(['id' => $c->id]);
            $comment->channel_id = $c->chan->id;
            $comment->comment = $c->message;
            $comment->created = $c->created;
            $comment->modified = $c->modified;
            $comment->name = $c->author->name ?? 'Аноним';
            $comment->email = $c->author->email ?? '';
            $comment->ip = $c->ip;
            $comment->status = $c->status;
            $comment->save();
        }
    }

    /**
     * Save array of reviews
     *
     * @return void
     */
    public function saveReviews(array $reviews): void
    {
        foreach ($reviews as $r) {
            $review = CackleReview::firstOrNew(['id' => $r->id]);
            $review->channel_id = $r->chan->id;
            $review->star = $r->star;
            $review->pros = $r->pros;
            $review->cons = $r->cons;
            $review->comment = $r->comment;
            $review->ip = $r->ip;
            $review->media = $r->media;
            $review->name = $r->author->name ?? 'Аноним';
            $review->email = $r->author->email ?? '';
            $review->status = $r->status;
            $review->created = $r->created;
            $review->modified = $r->modified;
            $review->save();
        }
    }
}
