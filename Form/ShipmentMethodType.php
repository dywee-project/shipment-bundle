<?php

namespace Dywee\ShipmentBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShipmentMethodType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',      TextType::class)
            ->add('deliver',    EntityType::class,          array(
                'class'         => 'DyweeShipmentBundle:Deliver',
                'choice_label'  => 'name',
                'required'      => true
            ))
            ->add('country',    EntityType::class,          array(
                'class'         => 'DyweeAddressBundle:Country',
                'choice_label'  => 'name',
                'required'      => false
            ))
            ->add('price',      MoneyType::class)
            ->add('minWeight', NumberType::class, array('required' => false))
            ->add('maxWeight', NumberType::class, array('required' => false))
            ->add('active',    CheckboxType::class, array('required' => false))
            ->add('save', SubmitType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Dywee\ShipmentBundle\Entity\ShipmentMethod'
        ));
    }
}
