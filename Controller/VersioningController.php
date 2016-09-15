<?php

namespace Dywee\ShipmentBundle\Controller;

use Dywee\ShipmentBundle\Entity\Deliver;
use Dywee\ShipmentBundle\Entity\ShippingMethod;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class VersioningController extends Controller
{
    /**
     * @Route(name="shipment_install", path="admin/install/shipment")
     */
    public function install()
    {
        $em = $this->getDoctrine()->getManager();

        $deliver1 = new Deliver();
        $deliver1->setName('Retrait en magasin');
        $deliver1->setActive(true);

        $shipmentMethod1 = new ShippingMethod();
        $shipmentMethod1->setName($deliver1->getName());
        $shipmentMethod1->setDeliver($deliver1);
        $shipmentMethod1->setActive(true);
        $shipmentMethod1->setPrice(0);

        $em->persist($deliver1);
        $em->persist($shipmentMethod1);

        $em->flush();

        return new Response('shipment installed');
    }
}
