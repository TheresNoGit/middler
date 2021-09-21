<?php
/**
 * GitHub
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
 * GitHub
 *
 * @category Class
 * @package  Middler
 * @author   sam <sam@theresnotime.co.uk>
 * @license  All Rights Reserved
 * @link     #
 * @since    Class available since Release 1.0.0
 */
class GitHub
{
    private array $_config;

    /**
     * Construct
     */
    function __construct()
    {
        $this->_config = json_decode(
            file_get_contents(
                __DIR__ . '/../../config.json'
            ),
            true
        );
    }

    /**
     * Wrapper to decode the payload from JSON to an array
     *
     * @return mixed
     */
    public function decodePayload()
    {
        if (isset($_POST['payload'])) {
            return json_decode($_POST['payload'], true);
        } else {
            return false;
        }
    }

    /**
     * Get the repo name from the payload
     *
     * @param array $payload The decoded payload array
     * 
     * @return mixed
     */
    public function getRepoName(array $payload)
    {
        if (isset($payload['repository'])) {
            return $payload['repository']['full_name'];
        } else {
            return false;
        }
    }

    /**
     * Get the relevant pager code from the payload
     *
     * @param array $payload The decoded payload array
     * 
     * @return mixed
     */
    public function getRepoPager(array $payload)
    {
        $repoName = $this->getRepoName($payload);
        if ($repoName) {
            if (isset($this->_config['repos'][$repoName]['pager'])) {
                return $this->_config['repos'][$repoName]['pager'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Get the hook event type
     *
     * @return mixed
     */
    public function getHookEvent()
    {
        return $_SERVER['HTTP_X_GITHUB_EVENT'];
    }
}