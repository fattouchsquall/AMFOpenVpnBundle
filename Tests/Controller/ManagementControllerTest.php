<?php

/*
 * This file is part of the AMFOpenVpnBundle package.
 *
 * (c) Amine Fattouch <http://github.com/fattouchsquall>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AMF\OpenVpnBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Functional test for the controller of server management.
 *
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
