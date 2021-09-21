<?php
/**
 * Discord
 * 
 * PHP version 8
 *
 * @category  Class
 * @package   Middler
 * @author    sam <sam@theresnotime.co.uk>
 * @copyright 2021 Sam
 * @license   All Rights Reserved
 * @version   GIT:1.0.0
 * @link      #
 * @since     File available since Release 1.0.0
 */
declare(strict_types=1);
namespace middler;

require_once __DIR__ . '/../../vendor/autoload.php';

/**
 * Discord
 *
 * @category Class
 * @package  Middler
 * @author   sam <sam@theresnotime.co.uk>
 * @license  All Rights Reserved
 * @link     #
 * @since    Class available since Release 1.0.0
 */
class Discord
{
    public string $webhook;
    private array $_config;

    /**
     * Construct
     *
     * @param string $feed Feed name
     */
    function __construct(string $feed)
    {
        $this->_config = json_decode(
            file_get_contents(
                __DIR__ . '/../../config.json'
            ),
            true
        );
        
        if (isset($config['discord_webhooks'][$feed])) {
            $this->webhook = $config['discord_webhooks'][$feed];
        }
    }

    /**
     * Get the relevant coded page ID from the config.json file
     *
     * @param mixed $pagerName Pager name
     * 
     * @return mixed
     */
    public function getCodedPage(mixed $pagerName)
    {
        if (isset($this->_config['coded_page'][$pagerName])) {
            return $this->_config['coded_page'][$pagerName];
        } else {
            return false;
        }
    }

    /**
     * Send a discord message
     *
     * @param string $content  Message content
     * @param string $username From user
     * 
     * @return mixed
     */
    public function sendMessage(string $content, string $username)
    {
        $webhookUrl = $this->webhook;

        $json_data = json_encode(
            [
                // Message
                "content" => $content,
                // Username
                "username" => $username
            ],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );


        $ch = curl_init($webhookUrl);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-type: application/json'
            )
        );
        curl_setopt(
            $ch,
            CURLOPT_POST,
            1
        );
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            $json_data
        );
        curl_setopt(
            $ch,
            CURLOPT_FOLLOWLOCATION,
            1
        );
        curl_setopt(
            $ch,
            CURLOPT_HEADER,
            0
        );
        curl_setopt(
            $ch,
            CURLOPT_RETURNTRANSFER,
            1
        );

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    /**
     * Send a GitHub push event message
     *
     * @param array $payload   Webhook payload
     * @param mixed $pagerName Coded page role to mention
     * 
     * @return mixed
     */
    public function sendGHPush(array $payload, mixed $pagerName = false)
    {
        if (isset($payload['repository'], $payload['after'], $payload['commits'])) {
            $repoName = $payload['repository']['full_name'];
            $commitUrl = $payload['repository']['html_url'] . "/commit/" . $payload['after'];
            $commitMessage = $payload['commits'][0]['message'];

            if ($pagerName) {
                $codedPage = $this->getCodedPage($pagerName);
                if ($codedPage) {
                    $pageStr = "\nCoded page: <@&$codedPage>\n";
                } else {
                    $pageStr = "";
                }
            } else {
                $pageStr = "";
            }
    
            $msg = "**$repoName just got updated!**\n$pageStr\nCommit message: $commitMessage\n\nURL: $commitUrl";
    
            return $this->sendMessage(
                $msg,
                "Middler|Team Feed"
            );
        } else {
            return false;
        }
    }
}