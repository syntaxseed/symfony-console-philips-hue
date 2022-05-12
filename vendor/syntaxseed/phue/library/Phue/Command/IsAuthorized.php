<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Command;

use Phue\Client;
use Phue\Transport\Exception\UnauthorizedUserException;

/**
 * Authenticate command
 */
class IsAuthorized implements CommandInterface
{

    /**
     * Send command
     *
     * @param Client $client
     *            Phue Client
     *
     * @return bool True if authorized, false if not
     */
    public function send(Client $client)
    {
        // Get response
        try {
            $client->getTransport()->sendRequest("/api/{$client->getUsername()}");
        } catch (UnauthorizedUserException $e) {
            return false;
        }
        
        return true;
    }
}
