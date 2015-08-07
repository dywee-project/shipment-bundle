<?php

namespace Dywee\ShipmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Shipment
 *
 * @ORM\Table(name="shipments")
 * @ORM\Entity(repositoryClass="Dywee\ShipmentBundle\Entity\ShipmentRepository")
 */
class Shipment
{
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
     * @ORM\Column(name="sendingIndex", type="smallint", nullable=true)
     */
    private $sendingIndex;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="departureDate", type="datetime")
     */
    private $departureDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updateDate", type="datetime", nullable=true)
     */
    private $updateDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="idDeliver", type="smallint", nullable=true)
     */
    private $idDeliver;

    /**
     * @var string
     *
     * @ORM\Column(name="tracingInfos", type="string", length=255, nullable=true)
     */
    private $tracingInfos;

    /**
     * @var integer
     *
     * @ORM\Column(name="state", type="smallint")
     */
    private $state = 0;

    /**
     * @ORM\OneToMany(targetEntity="Dywee\ShipmentBundle\Entity\ShipmentElement", mappedBy="shipment", cascade={"persist", "remove"})
     */
    private $shipmentElements;

    /**
     * @ORM\ManyToOne(targetEntity="Dywee\OrderBundle\Entity\BaseOrder", inversedBy="shipments")
     * @ORM\JoinColumn(nullable=true)
     */
    private $order;

    /**
     * @ORM\Column(name="mailSended", type="boolean")
     */
    private $mailSended = false;

    /**
     * @ORM\Column(name="weight", type="float")
     */
    private $weight = 0;


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
     * Set departureDate
     *
     * @param \DateTime $departureDate
     * @return Shipment
     */
    public function setDepartureDate($departureDate)
    {
        $this->departureDate = $departureDate;

        return $this;
    }

    /**
     * Get departureDate
     *
     * @return \DateTime 
     */
    public function getDepartureDate()
    {
        return $this->departureDate;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     * @return Shipment
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime 
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Shipment
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set idDeliver
     *
     * @param integer $idDeliver
     * @return Shipment
     */
    public function setIdDeliver($idDeliver)
    {
        $this->idDeliver = $idDeliver;

        return $this;
    }

    /**
     * Get idDeliver
     *
     * @return integer 
     */
    public function getIdDeliver()
    {
        return $this->idDeliver;
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
        $this->shipmentElements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add shipmentElements
     *
     * @param \Dywee\ShipmentBundle\Entity\ShipmentElement $shipmentElements
     * @return Shipment
     */
    public function addShipmentElement(\Dywee\ShipmentBundle\Entity\ShipmentElement $shipmentElements)
    {
        $this->shipmentElements[] = $shipmentElements;
        $shipmentElements->setShipment($this);

        return $this;
    }

    /**
     * Remove shipmentElements
     *
     * @param \Dywee\ShipmentBundle\Entity\ShipmentElement $shipmentElements
     */
    public function removeShipmentElement(\Dywee\ShipmentBundle\Entity\ShipmentElement $shipmentElements)
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
     * @return \Dywee\OrderBundle\Entity\BaseOrder 
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set old_id
     *
     * @param integer $oldId
     * @return Shipment
     */
    public function setOldId($oldId)
    {
        $this->old_id = $oldId;

        return $this;
    }

    /**
     * Get old_id
     *
     * @return integer 
     */
    public function getOldId()
    {
        return $this->old_id;
    }

    /**
     * Set order
     *
     * @param \Dywee\OrderBundle\Entity\BaseOrder $order
     * @return Shipment
     */
    public function setOrder(\Dywee\OrderBundle\Entity\BaseOrder $order = null)
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
     * Set mailSended
     *
     * @param boolean $mailSended
     *
     * @return Shipment
     */
    public function setMailSended($mailSended)
    {
        $this->mailSended = $mailSended;

        return $this;
    }

    /**
     * Get mailSended
     *
     * @return boolean
     */
    public function getMailSended()
    {
        return $this->mailSended;
    }
}
