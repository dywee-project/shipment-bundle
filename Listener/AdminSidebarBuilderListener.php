<?php

namespace Dywee\ShipmentBundle\Listener;

use Dywee\CoreBundle\DyweeCoreEvent;
use Dywee\CoreBundle\Event\AdminSidebarBuilderEvent;
use Dywee\OrderBundle\Service\OrderAdminSidebarHandler;
use Dywee\ShipmentBundle\Service\ShipmentAdminSidebarHandler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class AdminSidebarBuilderListener implements EventSubscriberInterface{
    private $shipmentAdminSidebarHandler;

    public function __construct(ShipmentAdminSidebarHandler $shipmentAdminSidebarHandler)
    {
        $this->shipmentAdminSidebarHandler = $shipmentAdminSidebarHandler;
    }

    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return array(
            DyweeCoreEvent::BUILD_ADMIN_SIDEBAR => array('addElementToSidebar', -10)
        );
    }

    public function addElementToSidebar(AdminSidebarBuilderEvent $adminSidebarBuilderEvent)
    {
        $adminSidebarBuilderEvent->addAdminElement($this->shipmentAdminSidebarHandler->getSideBarMenuElement());
    }

}