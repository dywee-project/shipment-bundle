<?php

namespace Dywee\ShipmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dywee\AddressBundle\Entity\Country;
use Dywee\CoreBundle\Traits\NameableEntity;

/**
 * ShipmentMethod
 *
 * @ORM\Table(name="shipment_methods")
 * @ORM\Entity(repositoryClass="Dywee\ShipmentBundle\Repository\ShipmentMethodRepository")
 */
class ShipmentMethod
{
    use NameableEntity;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $minWeight;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $maxWeight;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=10, scale=3)
     */
    private $price = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity="Dywee\AddressBundle\Entity\Country")
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity="Deliver")
     */
    private $deliver;

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
     * Set type
     *
     * @param string $type
     * @return ShipmentMethod
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set minWeight
     *
     * @param float $minWeight
     * @return ShipmentMethod
     */
    public function setMinWeight($minWeight)
    {
        $this->minWeight = $minWeight;

        return $this;
    }

    /**
     * Get minWeight
     *
     * @return float 
     */
    public function getMinWeight()
    {
        return $this->minWeight;
    }

    /**
     * Set maxWeight
     *
     * @param float $maxWeight
     * @return ShipmentMethod
     */
    public function setMaxWeight($maxWeight)
    {
        $this->maxWeight = $maxWeight;

        return $this;
    }

    /**
     * Get maxWeight
     *
     * @return float 
     */
    public function getMaxWeight()
    {
        return $this->maxWeight;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return ShipmentMethod
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return ShipmentMethod
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set country
     *
     * @param Country $country
     * @return ShipmentMethod
     */
    public function setCountry(Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set deliver
     *
     * @param Deliver $deliver
     * @return ShipmentMethod
     */
    public function setDeliver(Deliver $deliver = null)
    {
        $this->deliver = $deliver;

        return $this;
    }

    /**
     * Get deliver
     *
     * @return Deliver
     */
    public function getDeliver()
    {
        return $this->deliver;
    }

    public function getNameWithPrice()
    {
        return $this->getName(). ' ( '.number_format($this->getPrice(), 2).'€ )';
    }
}
