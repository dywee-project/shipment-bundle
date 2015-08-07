<?php

namespace Dywee\ShipmentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ShipmentController extends Controller
{
    public function tableAction($idOrder)
    {
        $cr = $this->getDoctrine()->getManager()->getRepository('DyweeOrderBundle:BaseOrder');
        $order = $cr->findOneById($idOrder);

        if($order != null)
            return $this->render('DyweeShipmentBundle:Shipment:table.html.twig', array('shipmentList' => $order->getShipments()));

        throw $this->createNotFoundException('La commande ne semble pas exister');
    }

    public function viewAction($id)
    {
        $sr = $this->getDoctrine()->getManager()->getRepository('DyweeShipmentBundle:Shipment');
        $shipment = $sr->findOneById($id);
        if($shipment != null)
            return $this->render('DyweeShipmentBundle:Shipment:view.html.twig', array('shipment' => $shipment));

        throw $this->createNotFoundException('Envoi non trouvé');
    }

    public function labelAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $sr = $em->getRepository('DyweeShipmentBundle:Shipment');

        $shipment = $sr->findOneById($id);

        $shipment->calculWeight();

        $deliver = $shipment->getOrder()->getDeliver();

        if($shipment)
        {
            $order = $shipment->getOrder();
            $shippingAddress = ($order->getShippingAddress() != null) ? $order->getShippingAddress() : $order->getBillingAddress();

            if($deliver->getId() == 1)
                throw $this->createNotFoundException('Retrait en magasin demandé');

            else if($deliver->getId() == 2) {

                $client = new \nusoap_client('http://www.mondialrelay.fr/WebService/Web_Services.asmx?WSDL', true);

                $params = array(
                    'Enseigne' => "BEBLCBLC",
                    'ModeCol' => 'REL',
                    'ModeLiv' => ($order->getDeliveryMethod() == 'LD1')?'HOM':$order->getDeliveryMethod(),
                    //'NDossier' => $commande->get('reference'),
                    //'NClient' => $livraison->get('id'),
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
                    'Dest_Tel1' => $shippingAddress->getMobile(),
                    'Dest_Mail' => $shippingAddress->getEmail(),

                    'Poids' => $shipment->getWeight(),
                    'NbColis' => '1',
                    'CRT_Valeur' => '0',
                    'COL_Rel_Pays' => 'BE',
                    'COL_Rel' => '006412'
                );

                if($shippingAddress->getCompanyName() != '')
                    $param['Dest_Ad2'] = $shippingAddress->getCompanyName();

                if($order->getDeliveryMethod() == '24R'){
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
                    $em->persist($shipment);
                    $em->flush();

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

                    return $this->redirect('http://www.mondialrelay.be/'.$result['WSI2_CreationEtiquetteResult']['URL_Etiquette']);
                }
                throw $this->createNotFoundException('erreur: '.$result['WSI2_CreationEtiquetteResult']['STAT']);
                }
            else if($deliver->getId() == 3)
                return new Reponse ('DPD a intégrer à la V3');
            else throw $this->createNotFoundException('Aucun livreur trouvé');
        }
        throw $this->createNotFoundException('Envoi non trouvé');
    }

    public function downloadAction($idShipment)
    {
        $or = $this->getDoctrine()->getManager()->getRepository('DyweeShipmentBundle:Shipment');
        $shipment = $or->findOneById($idShipment);

        if($shipment != null)
        {
            $fileName = /*'files/sendNotes/*/'envoi_'.$shipment->getId().'.pdf';
            if (!file_exists($fileName))
            {
                $note = $this->renderView('DyweeShipmentBundle:Shipment:note.html.twig', array('shipment' => $shipment));

                return $this->render('DyweeShipmentBundle:Shipment:note.html.twig', array('shipment' => $shipment));

                $pdfGenerator = $this->get('spraed.pdf.generator');

                $pdfGenerator->generatePDF($note, 'UTF-8');

                return new Response($pdfGenerator->generatePDF($note),
                    200,
                    array(
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'inline; filename="'.$fileName.'"'
                    )
                );//*/

                $this->get('knp_snappy.pdf')->generateFromHtml(
                    $note,
                    $fileName
                );//*/
            }

            $response = new Response();

            // Set headers
            $response->headers->set('Cache-Control', 'private');
            $response->headers->set('Content-type', mime_content_type($fileName));
            $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($fileName) . '";');
            $response->headers->set('Content-length', filesize($fileName));

            // Send headers before outputting anything
            $response->sendHeaders();

            $response->setContent(readfile($fileName));
            /*return new Response(
                $this->get('knp_snappy.pdf')->getOutput($this->generateUrl('dywee_shipment_note_view', array('idShipment' => $idShipment), true)),
                200,
                array(
                    'Content-Type'          => 'application/pdf',
                    'Content-Disposition'   => 'attachment; filename="invoice07.pdf"'
                )
            );*/
        }
        throw $this->createNotFoundException('Envoi introuvable');
    }

    public function viewNoteAction($idShipment)
    {
        $this->container->get('profiler')->disable();
        $or = $this->getDoctrine()->getManager()->getRepository('DyweeShipmentBundle:Shipment');
        $shipment = $or->findOneById($idShipment);

        if($shipment != null)
            return $this->render('DyweeShipmentBundle:Shipment:note.html.twig', array('shipment' => $shipment));

        throw $this->createNotFoundException('Envoi introuvable');
    }
}
