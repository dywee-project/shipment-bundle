<?php

namespace Dywee\ShipmentBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShippingMethodType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',      TextType::class, array('label' => 'shipmentMethod.form.name'))
            ->add('deliver',    EntityType::class,          array(
                'class'         => 'DyweeShipmentBundle:Deliver',
                'choice_label'  => 'name',
                'required'      => true,
                'label'         => 'shipmentMethod.form.deliver'
            ))
            ->add('type',       TextType::class, array('required' => false, 'label' => 'shipmentMethod.form.code'))
            ->add('country',    EntityType::class,          array(
                'class'         => 'DyweeAddressBundle:Country',
                'choice_label'  => 'name',
                'required'      => false
            ))
            ->add('price',      MoneyType::class)
            ->add('minWeight', NumberType::class, array('required' => false, 'label' => 'shipmentMethod.form.minWeight'))
            ->add('maxWeight', NumberType::class, array('required' => false, 'label' => 'shipmentMethod.form.maxWeight'))
            ->add('active',    CheckboxType::class, array('required' => false, 'label' => 'shipmentMethod.form.active'))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dywee\ShipmentBundle\Entity\ShippingMethod'
        ));
    }
}
