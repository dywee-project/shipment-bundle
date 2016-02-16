<?php

namespace Dywee\ShipmentBundle\Controller;

use Dywee\OrderBundle\Entity\DeliveryMethod;
use Dywee\ShipmentBundle\Entity\Shipment;
use Dywee\ShipmentBundle\Entity\ShipmentElement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ShipmentMethodController extends Controller
{
    public function tableAction()
    {
        $dr = $this->getDoctrine()->getManager()->getRepository('DyweeShipmentBundle:ShipmentMethod');
        $shipmentMethodList = $dr->findAll();

        return $this->render('DyweeShipmentBundle:ShipmentMethod:table.html.twig', array('shipmentMethodList' => $shipmentMethodList));
    }

    public function viewAction($id)
    {
        $sr = $this->getDoctrine()->getManager()->getRepository('DyweeShipmentBundle:ShipmentMethod');
        $shipmentMethod = $sr->findOneById($id);
        if($shipmentMethod == null)
            throw $this->createNotFoundException('Livreur non trouvé');

        return $this->render('DyweeShipmentBundle:ShipmentMethod:view.html.twig', array('shipmentMethodList' => $shipmentMethod));
    }

    public function searchAjaxAction()
    {
        $request = $this->container->get('request');
        if($request->isXmlHttpRequest())
        {
            $response = new Response();

            $country = $request->request->get('country');

            if(is_numeric($country))
            {
                $em = $this->getDoctrine()->getEntityManager();
                $smr = $em->getRepository('DyweeShipmentBundle:ShipmentMethod');

                $order = $this->get('session')->get('order');
                $or = $em->getRepository('DyweeOrderBundle:BaseOrder');
                $order = $or->findOneById($order->getId());

                $mondialRelay24R = array('name' => 'Livraison en point relais', 'price' => false);
                $mondialRelayHOM = array('name' => 'Livraison à domicile', 'price' => false);
                $dpd = array('name' => 'Livraison à domicile', 'price' => false);

                /*foreach($order->getOrderElements as $orderElement)
                {
                    $shipmentMethods = $smr->myfindBy($country, $orderElement->getProduct()->getWeight()*$orderElement->getQuantity());
                }

                //$shipmentMethods = $smr->myfindBy($country, $order->getWeight()); //*/

                $order->shipmentsCalculation(true);

                $containsBeers = false;
                $beersWeight = 0;

                //$coeff = count($order->getShipments());
                $coeff = 0;
                $totalCoeff = 0;

                $options = array();



                foreach($order->getOrderElements() as $orderElement)
                {
                    if($orderElement->getProduct()->getProductType() == 1)
                    {
                        $coeff = 1;
                        $totalCoeff = 1;

                        if(!$containsBeers)
                        {
                            $containsBeers = true;
                            $shipmentMethods = $smr->myfindBy($country, $order->getWeight(1));
                        }
                    }
                    else $shipmentMethods = $smr->myfindBy($country, $orderElement->getProduct()->getWeight());

                    if($orderElement->getProduct()->getProductType() == 3)
                    {
                        $coeff = $orderElement->getProduct()->getRecurrence();
                        $totalCoeff += $orderElement->getProduct()->getRecurrence()*$orderElement->getQuantity();
                    }
                    else if($orderElement->getProduct()->getProductType() == 2)
                    {
                        $coeff = 1;
                        $totalCoeff = 1;
                    }

                    if(isset($shipmentMethods))
                    foreach($shipmentMethods as $shipmentMethod)
                    {
                        if($shipmentMethod->getDeliver()->getId() == 2 && $shipmentMethod->getType() == '24R')
                        {
                            $mondialRelay24R['price'] =
                                ($orderElement->getProduct()->getProductType() == 1)?
                                    $shipmentMethod->getPrice()*$coeff:
                                    $shipmentMethod->getPrice()*$coeff*$orderElement->getQuantity();
                            //echo $mondialRelay24R['price'];
                            if(array_key_exists('24R', $options))
                                $options['24R']['price'] += $mondialRelay24R['price'];
                            else $options['24R'] = $mondialRelay24R;


                        }
                        else if($shipmentMethod->getDeliver()->getId() == 2 && $shipmentMethod->getType() == 'HOM')
                        {
                            $mondialRelayHOM['price'] =
                                ($orderElement->getProduct()->getProductType() == 1)?
                                    $shipmentMethod->getPrice()*$coeff:
                                    $shipmentMethod->getPrice()*$coeff*$orderElement->getQuantity();
                            if(array_key_exists('HOM', $options))
                                $options['HOM']['price'] += $mondialRelayHOM['price'];
                            else $options['HOM'] = $mondialRelayHOM;
                        }
                        else if($shipmentMethod->getDeliver()->getId() == 3)
                        {
                            $dpd['price'] =
                                ($orderElement->getProduct()->getProductType() == 1)?
                                    $shipmentMethod->getPrice()*$coeff:
                                    $shipmentMethod->getPrice()*$coeff*$orderElement->getQuantity();
                            if(array_key_exists('dpd', $options))
                                $options['dpd']['price'] += $dpd['price'];
                            else $options['dpd'] = $dpd;
                        }
                    }
                }

                if($order->containsOnlyOneType(3))
                    foreach($options as $key => $value)
                        $options[$key]['priceByShipment'] = $options[$key]['price']/$totalCoeff;

                $response->setContent(json_encode(array(
                    'type' => 'success',
                    'shippingOptions' => $options,
                    'coeff' => $totalCoeff
                )));

            }
            else $response->setContent(json_encode(array(
                'Error' => 'Pays invalide',
            )));

            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

    }
}
