<?php

namespace App\Form;

use App\Entity\Capteurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class CapteursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom')
        ->add('type', ChoiceType::class, [
            'choices' => [
                'LoopSense' => 'LoopSense',
                'CamSense' => 'CamSense',
                'WaveSense' => 'WaveSense',
            ],
            'label' => 'Type',
        ])
        ->add('latitude')
        ->add('longitude')
        ->add('dateInstallation');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Capteurs::class,
        ]);
    }
}
