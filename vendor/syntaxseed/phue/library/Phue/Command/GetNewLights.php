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

/**
 * Get new lights command
 */
class GetNewLights implements CommandInterface
{

    /**
     * Last scan
     *
     * @var string
     */
    protected string $lastScan;

    /**
     * Found lights
     *
     * @var array
     */
    protected array $lights = [];

    /**
     * Send command
     *
     * @param Client $client
     *            Phue Client
     *
     * @return self This object
     */
    public function send(Client $client)
    {
        // Get response
        $response = $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/lights/new"
        );
        
        $this->lastScan = $response->lastscan;
        
        // Remove scan from response
        unset($response->lastscan);
        
        // Iterate through left over properties as lights
        foreach ($response as $lightId => $light) {
            $this->lights[$lightId] = $light->name;
        }
        
        return $this;
    }

    /**
     * Get lights
     *
     * @return array List of new lights
     */
    public function getLights()
    {
        return $this->lights;
    }

    /**
     * Is scan currently active
     *
     * @return bool True if active, false if not
     */
    public function isScanActive()
    {
        return $this->lastScan === 'active';
    }
}
