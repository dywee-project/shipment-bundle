<?php

namespace Dywee\ShipmentBundle\Controller;

use Dywee\CoreBundle\Controller\ParentController;
use Dywee\ShipmentBundle\Entity\ShipmentRule;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShipmentRuleController extends ParentController
{
    /**
     * @Route(name="shipment_rule_add", path="admin/shipment/rule/add")
     */
    public function addAction(Request $request, $parameters = null)
    {
        return parent::addAction($request, $parameters); // TODO: Change the autogenerated stub
    }

    /**
     * @Route(name="shipment_rule_update", path="admin/shipment/rule/{id}/update")
     */
    public function myUpdateAction(ShipmentRule $shipmentRule, Request $request, $parameters = null)
    {
        return parent::updateAction($shipmentRule, $request, $parameters); // TODO: Change the autogenerated stub
    }

    /**
     * @Route(name="shipment_rule_table", path="admin/shipment/rule")
     */
    public function tableAction($parameters = null)
    {
        return parent::tableAction($parameters); // TODO: Change the autogenerated stub
    }

    /**
     * @Route(name="shipment_rule_delete", path="admin/shipment/rule/{id}/delete")
     */
    public function myDeleteAction(ShipmentRule $object, Request $request, $parameters = null)
    {
        return parent::deleteAction($object, $request, $parameters); // TODO: Change the autogenerated stub
    }
}
