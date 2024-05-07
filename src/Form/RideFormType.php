<?php

namespace App\Form;

use App\Entity\Ride;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use App\Validator\Constraints\CustomFormConstraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;



    class RideFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('departureTime', DateTimeType::class, [
                'widget' => 'single_text', // Renders the datetime input as a single text box
                'html5' => true, // Renders the input field with HTML5 type="datetime-local"
                // Optionally, you can add more configuration options here
            ])
            ->add('availableSeats')
            ->add('driver')
            ->add('startLocation')
            ->add('endLocation')
        
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ride::class,
        ]);
    }
}