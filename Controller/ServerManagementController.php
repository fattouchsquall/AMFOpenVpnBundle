<?php

/**
 * @package AMFOpenVpnBundle
 * @subpackage Controller
 * @author Mohamed Amine Fattouch <amine.fattouch@gmail.com>
 */

namespace AMF\OpenVPNBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @package AMFOpenVpnBundle
 * @subpackage Controller
 * @author Mohamed Amine Fattouch <amine.fattouch@gmail.com>
 */
class ServerManagementController extends Controller
{

    /**
     * Displays the list of all servers.
     * 
     * @return Response
     */
    public function indexAction()
    {
        $serverManagement = $this->get('amf_open_vpn.server_management');
        $servers          = $serverManagement->retrieveInfoOfAllServers();

        $pageReload = $this->container->getParameter('amf_open_vpn.config.reload');
        return $this->render('AMFOpenVpnBundle:Management:index.html.twig',
                        array('servers' => $servers, 'page_reload' => $pageReload));
    }
    
    /**
     * Retrieves infos for a given server.
     * 
     * @param string $number The number of server.
     * 
     * @return Response
     */
    public function retrieveInfosOfServerAction($number)
    {
        $serverManagement = $this->get('amf_open_vpn.server_management');
        $server           = $serverManagement->retrieveInfoOfServer($number);

        return $this->render('AMFOpenVpnBundle:Management:info_server.html.twig',
                        array('server' => $server));
    }

    /**
     * Kills a client.
     * 
     * @param string $serverNumber The number of server.
     * @param string $cn           The common name for the client.
     * 
     * @return RedirectResponse
     */
    public function killClientAction($serverNumber, $cn)
    {
        $serverManagement = $this->get('amf_open_vpn.server_management');
        $killed           = $serverManagement->killClient($serverNumber, $cn);
        if ($killed === true)
        {
            $this->get('session')->getFlashBag()->add(
                    'success',
                    sprintf('La connection du client %s a été interrompu', $cn)
            );
        }
        else
        {
            $this->get('session')->getFlashBag()->add(
                    'error',
                    sprintf('ERROR: Lors de la suppression de la connection de %s', $cn)
            );
        }
        
        $url = $this->generateUrl('amf_open_vpn_management_home');
        return $this->redirect($url);
    }
    
    /**
     * Displays log.
     * 
     * @return Response
     */
    public function showLogAction()
    {
        $serverManagement = $this->get('amf_open_vpn.server_management');
        $servers          = $serverManagement->retrieveLogOfAllServers();
        
        return $this->render('AMFOpenVpnBundle:Management:show_log.html.twig', 
                        array('servers' => $servers));
    }
    
    /**
     * Shows version for a selected server.
     * 
     * @param string $serverNumber The number of server.
     * 
     * @return Response
     */
    public function showVersionAction(Request $request, $serverNumber)
    {
        if ($request->isXmlHttpRequest())
        {   
            $serverManagement = $this->get('amf_open_vpn.server_management');
            $server           = $serverManagement->retrieveVersionOfServer($serverNumber);
        
            return $this->render('AMFOpenVpnBundle:Management:show_version_modal.html.twig', 
                        array('server' => $server));
        }
        
        return new Response();
    }
}

