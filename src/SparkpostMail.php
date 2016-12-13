<?php
namespace Microit\LaravelAdminBaseStandalone;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Ivory\HttpAdapter\Guzzle6HttpAdapter;
use SparkPost\SparkPost;

/**
 * Send an email via SparkPost
 * Class SparkpostMail
 * @package Microit\LaravelAdminBaseStandalone
 */
class SparkpostMail
{
    public $spark;
    public $options;

    protected $auth;
    protected $http_adapter;
    protected $recipients = [];

    /**
     * Set up a sparkpost mail instance
     */
    public function __construct()
    {
        $this->auth = config('services.sparkpost.secret');
        $this->http_adapter = new Guzzle6HttpAdapter(new Client());
        $this->spark = new SparkPost($this->http_adapter, $this->auth);

        $this->options = [
            'from'          => config('mail.from.name') . '<' . config('mail.from.address') . '>',
            'trackOpens'    => true,
            'trackClicks'   => true,
            'inlineCss'     => true,
            'transactional' => true
        ];
    }

    public function setOptions($options)
    {
        $this->options = array_merge($this->options, $options);
    }

    public function addRecipient($email, $name)
    {
        $this->recipients[] = ["address" => ["email" => $email, "name" => $name]];
    }

    public function setCampaign($name)
    {
        $this->options['campaign'] = $name;
    }

    public function setDescription($description)
    {
        $this->options['description'] = $description;
    }

    public function setSubject($subject)
    {
        $this->options['subject'] = $subject;
    }

    public function setHtml($html)
    {
        $this->options['html'] = (string) $html;
    }

    public function sendEmail()
    {
        $this->options['recipients'] = $this->recipients;
        $results = '';
        try {
            $results = $this->spark->transmission->send($this->options);
            Log::error('SparkPost Mail: ' . $results);
        } catch (\Exception $e) {
            Log::error('SparkPost API: ' . $e->getMessage());
        }
        return $results;
    }
}