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

        $shipmentMethod = new ShippingMethod();
        $shipmentMethod->setName($deliver1->getName());
        $shipmentMethod->setDeliver($deliver1);
        $shipmentMethod->setActive(true);
        $shipmentMethod->setPrice(0);

        $deliver2 = new Deliver();
        $deliver2->setName('Mondial Relay');
        $deliver2->setActive(true);

        $shipmentMethod1 = new ShippingMethod();
        $shipmentMethod1->setName($deliver2->getName(). ' 24R');
        $shipmentMethod1->setType('24R');
        $shipmentMethod1->setDeliver($deliver2);
        $shipmentMethod1->setActive(true);
        $shipmentMethod1->setPrice(3.99);

        $shipmentMethod2 = new ShippingMethod();
        $shipmentMethod2->setName($deliver2->getName(). ' HOM');
        $shipmentMethod2->setType('HOM');
        $shipmentMethod2->setDeliver($deliver2);
        $shipmentMethod2->setActive(true);
        $shipmentMethod2->setPrice(5.99);

        $em->persist($deliver1);
        $em->persist($deliver2);
        $em->persist($shipmentMethod);
        $em->persist($shipmentMethod1);
        $em->persist($shipmentMethod2);

        $em->flush();

        return new Response('shipment installed');
    }
}
