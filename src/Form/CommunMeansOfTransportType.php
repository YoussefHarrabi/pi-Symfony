<?php

namespace App\Form;

use App\Entity\CommunMeansOfTransport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CommunMeansOfTransportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('registrationNumber')
          //  ->add('type')
          ->add('type', ChoiceType::class, [
            'label' => 'Your Type:',
            'choices' => [
                    //  'car' => 'car',
                      'Bus' => 'Bus',
                      'Train' => 'Train',
                          // Add more options as needed
                         ],
             ])
   
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommunMeansOfTransport::class,
        ]);
    }
}
