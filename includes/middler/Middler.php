<?php
/**
 * Middler
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
 * Middler
 *
 * @category Class
 * @package  Middler
 * @author   sam <sam@theresnotime.co.uk>
 * @license  All Rights Reserved
 * @link     #
 * @since    Class available since Release 1.0.0
 */
class Middler
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
     * Test a given repo against the allowed_regex config
     *
     * @param array $payload The decoded payload array
     * 
     * @return bool
     */
    public function checkAllowedRegex(array $payload)
    {
        $regex = $this->_config['allowed_regex'];
        $repoName = (new GitHub())->getRepoName($payload);
        if ($repoName) {
            return preg_match("/$regex/", $repoName);
        } else {
            return false;
        }
    }

    /**
     * Wrapper to output a JSON response
     *
     * @param array $return Array containing response
     * 
     * @return void
     */
    public function JSONResponse(array $return)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($return);
    }
}