<?php
/**
 * API endpoint
 * 
 * PHP version 8
 *
 * @category  API
 * @package   Middler
 * @author    sam <sam@theresnotime.co.uk>
 * @copyright 2021 Sam
 * @license   All Rights Reserved
 * @version   GIT:1.0.0
 * @link      #
 * @since     File available since Release 1.0.0
 */
declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';
$github = new middler\GitHub();

if ($github->getHookEvent() === "push") {
    
    $payload = $github->decodePayload($_POST['payload']);

    if ($payload) {
        $feeds = $github->getRepoFeeds($payload);
        foreach ($feeds as $feed) {
            $discord = new middler\Discord($feed);
            $pager = $github->getRepoPager($payload);
            $discord->sendGHPush($payload, $pager);
        }
    }
}