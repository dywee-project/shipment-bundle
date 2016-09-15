<?php

namespace Dywee\ShipmentBundle\Gateway;

use Doctrine\ORM\EntityManager;
use Dywee\ShipmentBundle\Entity\Shipment;

class MondialRelay{

    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getLabel(Shipment $shipment)
    {
        if($shipment)
        {
            $order = $shipment->getOrder();
            $shippingAddress = $order->getShippingAddress() ?? $order->getBillingAddress();

                $client = new \nusoap_client('http://www.mondialrelay.fr/WebService/Web_Services.asmx?WSDL', true);

                $params = array(
                    'Enseigne' => "BEBLCBLC",
                    'ModeCol' => 'REL',
                    'ModeLiv' => $shipment->getShippingMethod()->getType(),
                    'Expe_Langage' => 'FR',
                    'Expe_Ad1' => 'La Belgique une Fois',
                    'Expe_Ad3' => 'Vieux chemin de nivelles 124',
                    'Expe_Ville' => 'Braine-le-chateau',
                    'Expe_CP' => '1440',
                    'Expe_Pays' => 'BE',
                    'Expe_Tel1' => '+330635932525',
                    'Expe_Mail' => 'info@labelgiqueunefois.com',

                    'Dest_Langage' => 'FR',

                    'Dest_Ad1' => $shippingAddress->getFirstName().' '.$shippingAddress->getLastName(),
                    'Dest_Ad3' => $shippingAddress->getAddress1(),
                    'Dest_Ad4' => $shippingAddress->getAddress2(),
                    'Dest_Ville' => $shippingAddress->getCityString(),
                    'Dest_CP' => $shippingAddress->getZip(),
                    'Dest_Pays' => $shippingAddress->getCountry()->getIso(),
                    'Dest_Tel1' => '+'.$shippingAddress->getMobile()->getCountryCode().$shippingAddress->getMobile()->getNationalNumber(),
                    'Dest_Mail' => $shippingAddress->getEmail(),

                    'Poids' => $shipment->getWeight(),
                    'NbColis' => '1',
                    'CRT_Valeur' => '0',
                    'COL_Rel_Pays' => 'BE',
                    'COL_Rel' => '006412'
                );

                if($shippingAddress->getCompany() != '')
                    $param['Dest_Ad2'] = $shippingAddress->getCompany();

                if($shipment->getShippingMethod()->getType() == '24R'){
                    $relai = explode('-', $order->getDeliveryInfo());
                    $params['LIV_Rel_Pays'] = $relai[0];
                    $params['LIV_Rel'] = $relai[1];
                }

                //echo '<pre>'; print_r($params); echo '</pre>'; exit;


                $security = '';
                foreach($params as $param)
                    $security .= $param;
                $security .= 'xgG1mpth';

                $params['Security'] = strtoupper(md5($security));

                $result = $client->call('WSI2_CreationEtiquette', $params, 'http://www.mondialrelay.fr/webservice/', 'http://www.mondialrelay.fr/webservice/WSI2_CreationEtiquette');

                //print_r($result['WSI2_CreationEtiquetteResult']);

                if(isset($result['WSI2_CreationEtiquetteResult']['STAT']) && is_numeric($result['WSI2_CreationEtiquetteResult']['STAT']) && $result['WSI2_CreationEtiquetteResult']['STAT'] == 0){
                    $shipment->setTracingInfos($result['WSI2_CreationEtiquetteResult']['ExpeditionNum']);
                    $shipment->setState(1);
                    $shipment->setUpdateDate(new \DateTime());

                    //Envoi de l'email disant que le colis a quitté l'entrepot
                    if (!$shipment->getMailSended()) {
                        $message = \Swift_Message::newInstance()
                            ->setSubject('La Belgique une fois - Votre colis est parti')
                            ->setFrom('info@labelgiqueunefois.be')
                            ->setTo($shipment->getOrder()->getBillingAddress()->getEmail())
                            ->setBody($this->renderView('DyweeOrderBundle:Email:mail-step3.html.twig', array('order' => $shipment->getOrder())));
                        $message->setContentType("text/html");
                        $this->get('mailer')->send($message);
                        if ($shipment->getOrder()->getIsGift() == 1) {
                            $message = \Swift_Message::newInstance()
                                ->setSubject('La Belgique une fois - Un colis pour vous est parti')
                                ->setFrom('info@labelgiqueunefois.be')
                                ->setTo($shipment->getOrder()->getShippingAddress()->getEmail())
                                ->setBody($this->renderView('DyweeOrderBundle:Email:mail-step4.html.twig', array('order' => $shipment->getOrder())));
                            $message->setContentType("text/html");
                            $this->get('mailer')->send($message);
                        }
                        $shipment->setMailSended(true);

                    }

                    $em->persist($shipment);
                    $em->flush();

                    return $this->redirect('http://www.mondialrelay.be/'.$result['WSI2_CreationEtiquetteResult']['URL_Etiquette']);
                }
                throw $this->createNotFoundException('erreur: '.$result['WSI2_CreationEtiquetteResult']['STAT']);
            }
    }

}