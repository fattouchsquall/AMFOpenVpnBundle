<?php

/*
 * This file is part of the AMFOpenVpnBundle package.
 *
 * (c) Amine Fattouch <http://github.com/fattouchsquall>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AMF\OpenVpnBundle\EndPoint;

/**
 * The management interface for server.
 * 
 * @package AMFOpenVpnBundle
 * @subpackage EndPoint
 * @author Mohamed Amine Fattouch <amine.fattouch@gmail.com>
 */
class ServerManagement
{

    /**
     * @var array
     */
    protected $openvpnConfig;

    
    /**
     * Constructor class.
     * 
     * @param array $openvpnConfig The config for the open vpn connexion.
     */
    public function __construct(array $openvpnConfig=array())
    {
        $this->openvpnConfig = $openvpnConfig;
    }

    /**
     * Kills a client selected by its common name.
     * 
     * @param string $serverNumber The number of server.
     * @param string $cn           The common name for the client.
     * 
     * @return boolean
     */
    public function killClient($serverNumber, $cn)
    {
        $fp = $this->connect($serverNumber);
        
        fwrite($fp, "kill " . $cn . "\n\n\n");
        usleep(250000);
        fwrite($fp, "quit\n\n\n");
        usleep(250000);
        while (!feof($fp))
        {
            $line = fgets($fp, 128);
            if (strpos($line, $cn) !== false)
            {
                if (strpos($line, 'SUCCESS') === false)
                {
                    return false;
                }
                else
                {
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
     * Returns the info for a one server.
     * 
     * @param string $serverNumber The number of server.
     * 
     * @return type
     */
    public function retrieveInfoOfServer($serverNumber)
    {
        $fp = $this->connect($serverNumber);
        
        fwrite($fp, "status\n\n\n");
        usleep(250000);
        fwrite($fp, "quit\n\n\n");
        usleep(250000);
        $clients = array();
        $client  = $routing = false;
        while (!feof($fp))
        {
            $line = fgets($fp, 128);
            if (substr($line, 0, 13) == "ROUTING TABLE")
            {
                $client = false;
            }
            if ($client === true)
            {
                $clientData                  = explode(',', $line);
                $clientLines[$clientData[1]] = array($clientData[2], $clientData[3], $clientData[4]);
            }
            if (substr($line, 0, 11) == "Common Name")
            {
                $client = true;
            }

            if (substr($line, 0, 12) == "GLOBAL STATS")
            {
                $routing = false;
            }
            if ($routing === true)
            {
                $routeData = explode(',', $line);
                array_push($clients,
                        array_merge($routeData, $clientLines[$routeData[2]]));
            }
            if (substr($line, 0, 15) == "Virtual Address")
            {
                $routing = true;
            }
        }

        fclose($fp);
        
        $nameServer = $this->openvpnConfig[$serverNumber]['name'];
        $server     = array('result' => true, 
            'clients' => $clients, 
            'name'    => $nameServer
        );
        
        return $server;
    }

    /**
     * Returns the infos of all openvpn servers.
     * 
     * @return array
     */
    public function retrieveInfoOfAllServers()
    {
        $servers = array();
        foreach ($this->openvpnConfig as $key => $config)
        {
            $servers[$key] = $this->retrieveInfoOfServer($key);
        }
        return $servers;
    }
    
    /**
     * Returns the log of all openvpn servers.
     * 
     * @return array
     */
    public function retrieveLogOfAllServers()
    {
        $servers = array();
        foreach ($this->openvpnConfig as $key => $config)
        {
            $servers[$key] = $this->retrieveLogOfServer($key);
        }
        return $servers;
    }
    
    /**
     * Returns the log for a one server.
     * 
     * @param string $serverNumber The number of server.
     * 
     * @return string
     */
    public function retrieveLogOfServer($serverNumber)
    {
        $fp = $this->connect($serverNumber);
        
        fwrite($fp, "log all\n\n\n");
        usleep(250000);
        fwrite($fp, "quit\n\n\n");
        usleep(250000);
        
        $logs = array();
        while (!feof($fp))
        {
            $line = fgets($fp, 128);
            if (strpos($line, 'Management Interface Version') > 0 || strpos($line, 'END') > 0)
            {
                continue;
            }
            $lineData = explode(',', $line);
            if (array_key_exists(2, $lineData))
            {
                $infoData = preg_split('#(?<!\\\)\:#' , $lineData[2]);
                if ($lineData[1] === ''  && array_key_exists(1, $infoData))
                {
                    $date           = date('H:m:s d/m/Y', $lineData[0]);
                    $logs['info'][] = array('date' => $date, 'interface' => $infoData[0], 'message' => $infoData[1]);
                }
            }
        }
        
        $logs['info'] = array_reverse($logs['info']);
        
        $nameServer = $this->openvpnConfig[$serverNumber]['name'];
        $server     = array('result' => true, 
            'logs' => $logs, 
            'name' => $nameServer
        );
        
        return $server;
    }
    
    /**
     * Returns the version for a selected server.
     * 
     * @param string $serverNumber The number of server.
     * 
     * @return string
     */
    public function retrieveVersionOfServer($serverNumber)
    {
        $fp = $this->connect($serverNumber);
        
        fwrite($fp, "version\n\n\n");
        usleep(250000);
        fwrite($fp, "quit\n\n\n");
        usleep(250000);
        
        $version = '';
        while (!feof($fp))
        {
            
            $line = fgets($fp, 128);
            
            $lineData = explode(' ', $line);
            if (array_key_exists(2, $lineData) && array_key_exists(3, $lineData))
            {
                if ($lineData[1] === 'Version:')
                {
                    $version = sprintf('La version du serveur %s est: %s', $lineData[2], $lineData[3]);
                    break;
                }
            }
        }
        
        $state = $this->retrieveStateOfServer($serverNumber);
        
        $nameServer = $this->openvpnConfig[$serverNumber]['name'];
        $server     = array('version' => $version, 
            'state' => $state,
            'name'  => $nameServer
        );
        
        return $server;
    }
    
    /**
     * Returns the state for a selected server.
     * 
     * @param string $serverNumber The number of server.
     * 
     * @return string
     */
    public function retrieveStateOfServer($serverNumber)
    {
        $fp = $this->connect($serverNumber);
        
        fwrite($fp, "state\n\n\n");
        usleep(250000);
        fwrite($fp, "quit\n\n\n");
        usleep(250000);
        
        $addressIp = '';
        $stateName     = '';
        while (!feof($fp))
        {
            $line = fgets($fp, 128);
            
            if (strpos($line, 'Management Interface Version') > 0)
            {
                continue;
            }
            $lineData = explode(',', $line);
            $addressIp = $lineData[3];
            $stateName = $lineData[1];
            break;
        }
        
        return array('addressIp' => $addressIp, 'name' => $stateName);
    }
    
    /**
     * Opens a telnet socket with vpn server.
     * 
     * @param string $serverNumber The number of server.
     * 
     * @return boolean
     */
    protected function connect($serverNumber)
    {
        if (!isset($this->openvpnConfig[$serverNumber]))
        {
            return false;
        }
        
        $hostVpn = $this->openvpnConfig[$serverNumber]['telnet_ip'];
        $portVpn = $this->openvpnConfig[$serverNumber]['telnet_port'];
        // open a telnet socket with openvpn server.
        $fp = @fsockopen($hostVpn, $portVpn, $errno, $errstr, 3);
        if ($fp === false)
        {
            throw new \Exception(sprintf('Error numéro %s : %s', $errno, $errstr));
        }
        
        $passwordVpn = $this->openvpnConfig[$serverNumber]['telnet_password'];
        if (isset($passwordVpn))
        {
            fwrite($fp, $passwordVpn . "\n\n\n");
            usleep(250000);
        }
        
        return $fp;
    }
}
