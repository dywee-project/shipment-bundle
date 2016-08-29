<?php

namespace Dywee\ShipmentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Dywee\CoreBundle\Traits\TimeDelimitableEntity;
use Dywee\OrderBundle\Entity\BaseOrderInterface;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Shipment
 *
 * @ORM\Table(name="shipments")
 * @ORM\Entity(repositoryClass="Dywee\ShipmentBundle\Repository\ShipmentRepository")
 */
class Shipment
{

    const STATE_NOT_PREPARED = 'shipment.state.not_prepared';
    const STATE_PREPARING = 'shipment.state.preparing';
    const STATE_WAITING = 'shipment.state.waiting';
    const STATE_SHIPPING = 'shipment.state.shipping';
    const STATE_SHIPPED = 'shipped';
    const STATE_WAITING_CUSTOMER = 'shipment.state.waiting_customer';
    const STATE_RETURNED = 'shipment.state.returned';

    use TimestampableEntity;
    use TimeDelimitableEntity;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $sendingIndex;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tracingInfos;

    /**
     * @var integer
     *
     * @ORM\Column(type="text", length=255)
     */
    private $state = self::STATE_NOT_PREPARED;

    /**
     * @ORM\OneToMany(targetEntity="ShipmentElement", mappedBy="shipment", cascade={"persist", "remove"})
     */
    private $shipmentElements;

    /**
     * @ORM\ManyToOne(targetEntity="Dywee\OrderBundle\Entity\BaseOrder", inversedBy="shipments")
     * @ORM\JoinColumn(nullable=true)
     */
    private $order;

    /**
     * @ORM\Column(type="smallint")
     */
    private $mailStep = 0;

    /**
     * @ORM\Column(name="weight", type="decimal", precision=10, scale=3)
     */
    private $weight = 0;

    /**
     * @ORM\ManyToOne(targetEntity="ShipmentMethod")
     */
    private $shipmentMethod;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set sendingIndex
     *
     * @param integer $sendingIndex
     * @return Shipment
     */
    public function setSendingIndex($sendingIndex)
    {
        $this->sendingIndex = $sendingIndex;

        return $this;
    }

    /**
     * Get sendingIndex
     *
     * @return integer 
     */
    public function getSendingIndex()
    {
        return $this->sendingIndex;
    }
    

    /**
     * Set tracingInfos
     *
     * @param string $tracingInfos
     * @return Shipment
     */
    public function setTracingInfos($tracingInfos)
    {
        $this->tracingInfos = $tracingInfos;

        return $this;
    }

    /**
     * Get tracingInfos
     *
     * @return string 
     */
    public function getTracingInfos()
    {
        return $this->tracingInfos;
    }

    /**
     * Set state
     *
     * @param integer $state
     * @return Shipment
     */
    public function setState($state)
    {
        if($state != $this->state)
        {
            $this->state = $state;
            $this->setMailSended(false);
        }


        return $this;
    }

    /**
     * Get state
     *
     * @return integer 
     */
    public function getState()
    {
        return $this->state;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->shipmentElements = new ArrayCollection();
    }

    /**
     * Add shipmentElements
     *
     * @param ShipmentElement $shipmentElements
     * @return Shipment
     */
    public function addShipmentElement(ShipmentElement $shipmentElements)
    {
        $this->shipmentElements[] = $shipmentElements;
        $shipmentElements->setShipment($this);

        return $this;
    }

    /**
     * Remove shipmentElements
     *
     * @param ShipmentElement $shipmentElements
     */
    public function removeShipmentElement(ShipmentElement $shipmentElements)
    {
        $this->shipmentElements->removeElement($shipmentElements);
    }

    /**
     * Get shipmentElements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getShipmentElements()
    {
        return $this->shipmentElements;
    }

    /**
     * Get order
     *
     * @return BaseOrderInterface
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set order
     *
     * @param BaseOrderInterface $order
     * @return Shipment
     */
    public function setOrder(BaseOrderInterface $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Set weight
     *
     * @param float $weight
     * @return Shipment
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return float 
     */
    public function getWeight()
    {
        return $this->weight;
    }

    public function calculWeight()
    {
        $weight = 0;
        foreach($this->getShipmentElements() as $shipmentElement)
            $weight += $shipmentElement->getWeight();

        $this->setWeight($weight);
    }

    /**
     * Set mailStep
     *
     * @param int $mailStep
     *
     * @return Shipment
     */
    public function setMailStep($mailStep)
    {
        $this->mailStep= $mailStep;

        return $this;
    }

    /**
     * Get mailStep
     *
     * @return int
     */
    public function getMailStep()
    {
        return $this->mailStep;
    }

    /**
     * alias
     * @param $date
     * @return TimeDelimitableEntity
     */
    public function setDepartureAt($date)
    {
        return $this->setBeginAt($date);
    }

    /**
     * alias
     * @return \DateTime
     */
    public function getDepartureAt()
    {
        return $this->getBeginAt();
    }

    /**
     * @return mixed
     */
    public function getShipmentMethod()
    {
        return $this->shipmentMethod;
    }

    /**
     * @param mixed $shipmentMethod
     * @return Shipment
     */
    public function setShipmentMethod($shipmentMethod)
    {
        $this->shipmentMethod = $shipmentMethod;
        return $this;
    }


}
