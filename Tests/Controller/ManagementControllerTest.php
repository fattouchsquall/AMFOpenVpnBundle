<?php

/**
 * Functional test for the controller of server management.
 * 
 * @package AMFOpenVpnBundle
 * @subpackage Controller
 * @author Mohamed Amine Fattouch <amine.fattouch@gmail.com>
 */
namespace AMF\OpenVpnBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Functional test for the controller of server management.
 * 
 * @package AMFOpenVpnBundle
 * @subpackage Controller
 * @author Mohamed Amine Fattouch <amine.fattouch@gmail.com>
 */
class ManagementControllerTest extends WebTestCase
{
    
    /**
     * Test the action of retrieve infos of server.
     */
    public function testRetrieveInfosOfServer()
    {
        $client = static::createClient();

        $$client->request('GET', '/serveur/info/1');
        
        $this->assertTrue($client->getResponse()->isOk());
    }
}
