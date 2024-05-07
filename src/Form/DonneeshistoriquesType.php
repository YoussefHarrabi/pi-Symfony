<?php

namespace App\Form;

use App\Entity\Donneeshistoriques;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Capteurs;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class DonneeshistoriquesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder->add('idCapteur', EntityType::class, [
        // Spécifiez l'entité à laquelle ce champ est lié
        'class' => Capteurs::class,
        // Le champ à afficher dans la liste déroulante
        'choice_label' => 'id', // ou un autre champ approprié de Capteurs
        'label' => 'ID Capteur',
    ])
    
        ->add('niveauEmbouteillage', RangeType::class, [
            'attr' => [
                'min' => 1,
                'max' => 5,
            ],
            'label' => 'Niveau d\'embouteillage',
        ])
        ->add('alerte')
    ;
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Donneeshistoriques::class,
        ]);
    }
}
