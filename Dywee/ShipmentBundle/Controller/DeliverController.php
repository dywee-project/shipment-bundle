<?php

namespace Dywee\ShipmentBundle\Controller;

use Dywee\OrderBundle\Entity\Deliver;
use Dywee\ShipmentBundle\Entity\Shipment;
use Dywee\ShipmentBundle\Entity\ShipmentElement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DeliverController extends Controller
{
    public function tableAction()
    {
        $dr = $this->getDoctrine()->getManager()->getRepository('DyweeShipmentBundle:Deliver');
        $deliverList = $dr->findAll();

        return $this->render('DyweeShipmentBundle:Deliver:table.html.twig', array('deliverList' => $deliverList));
    }

    public function viewAction($id)
    {
        $sr = $this->getDoctrine()->getManager()->getRepository('DyweeShipmentBundle:Deliver');
        $deliver = $sr->findOneById($id);
        if($deliver != null)
        {
            return $this->render('DyweeShipmentBundle:Deliver:view.html.twig', array('deliver' => $deliver));
        }
        throw $this->createNotFoundException('Livreur non trouvé');
    }

    public function addAction()
    {
        //$this->hydrate();
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
