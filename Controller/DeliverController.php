<?php

namespace Dywee\ShipmentBundle\Controller;

use Dywee\ShipmentBundle\Entity\Deliver;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DeliverController extends Controller
{
    /**
     * @return Response
     *
     * @Route(name="deliver_table", path="admin/deliver")
     */
    public function tableAction()
    {
        $dr = $this->getDoctrine()->getRepository('DyweeShipmentBundle:Deliver');
        $deliverList = $dr->findAll();

        return $this->render('DyweeShipmentBundle:Deliver:table.html.twig', array('delivers' => $deliverList));
    }

    public function viewAction(Deliver $deliver)
    {
        return $this->render('DyweeShipmentBundle:Deliver:view.html.twig', array('deliver' => $deliver));
    }

    public function addAction()
    {
        return new Response('add');
    }

    public function updateAction()
    {
        return new Response('update');
    }

    public function deleteAction()
    {
        return new Response('delete');
    }
}
