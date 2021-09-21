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
$middler = new middler\Middler();

if ($github->getHookEvent() === "push") {
    $payload = $github->decodePayload($_POST['payload']);

    if ($payload) {
        if ($middler->checkAllowedRegex($payload)) {
            $feeds = $github->getRepoFeeds($payload);
            foreach ($feeds as $feed) {
                $discord = new middler\Discord($feed);
                $pager = $github->getRepoPager($payload);
                $discord->sendGHPush($payload, $pager);
            }
            $return = array(
                'success' => true,
                'detail' => 'message(s) sent'
            );
        } else {
            $return = array(
                'success' => false,
                'detail' => 'repo not permitted'
            );
        }
    } else {
        $return = array(
            'success' => false,
            'detail' => 'unable to decode payload'
        );
    }
} else {
    $return = array(
        'success' => false,
        'detail' => 'webhook event not supported'
    );
}

if (isset($return)) {
    $middler->JSONResponse($return);
} else {
    $middler->JSONResponse(
        array(
            'success' => false,
            'detail' => 'something went very wrong'
        )
    );
}