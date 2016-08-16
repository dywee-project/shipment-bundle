<?php

namespace Dywee\ShipmentBundle\Service;

use Symfony\Component\Routing\Router;

class ShipmentAdminSidebarHandler
{

    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getSideBarMenuElement()
    {
        $menu = array(
            'key' => 'shipment',
            'icon' => 'fa fa-truck',
            'label' => 'shipment.sidebar.label',
            'children' => array(
                array(
                    'icon' => 'fa fa-list-alt',
                    'label' => 'deliver.sidebar.table',
                    'route' => $this->router->generate('deliver_table')
                ),
                array(
                    'icon' => 'fa fa-list-alt',
                    'label' => 'shipment.sidebar.table',
                    'route' => $this->router->generate('order_table')
                ),
                array(
                    'icon' => 'fa fa-cog',
                    'label' => 'shipment.sidebar.invoice',
                    'route' => $this->router->generate('order_reference_update')
                ),
            )
        );

        return $menu;
    }
}