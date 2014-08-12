<?php

/**
 * @package AMFOpenVpnBundle
 * @subpackage EndPoint
 * @author Mohamed Amine Fattouch <amine.fattouch@gmail.com>
 */

namespace AMF\OpenVpnBundle\EndPoint;

/**
 * The management interface for server.
 * 
 * @package AMFOpenVpnBundle
 * @subpackage EndPoint
 * @author Mohamed Amine Fattouch <amine.fattouch@gmail.com>
 */
interface ServerManagementInterface
{
    
    /**
     * Kills a client selected by its common name.
     * 
     * @param string $serverNumber The number of server.
     * @param string $cn           The common name for the client.
     * 
     * @return boolean
     */
    public function killClient($serverNumber, $cn);

    /**
     * Returns the info for a one server.
     * 
     * @param string $serverNumber The number of server.
     * 
     * @return type
     */
    public function retrieveInfoOfServer($serverNumber);

    /**
     * Returns the infos of all openvpn servers.
     * 
     * @return array
     */
    public function retrieveInfoOfAllServers();
    
    /**
     * Returns the log of all openvpn servers.
     * 
     * @return array
     */
    public function retrieveLogOfAllServers();
    
    /**
     * Returns the log for a one server.
     * 
     * @param string $serverNumber The number of server.
     * 
     * @return string
     */
    public function retrieveLogOfServer($serverNumber);
    
    /**
     * Returns the version for a selected server.
     * 
     * @param string $serverNumber The number of server.
     * 
     * @return string
     */
    public function retrieveVersionOfServer($serverNumber);
    
    /**
     * Returns the state for a selected server.
     * 
     * @param string $serverNumber The number of server.
     * 
     * @return string
     */
    public function retrieveStateOfServer($serverNumber);
}