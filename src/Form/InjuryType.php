<?php

namespace App\Form;

use App\Entity\Injury;
use App\Entity\Incident;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
class InjuryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $types = [
            "Fracture",
            "Sprain",
            "Burn",
            "Cut",
            "Bruise",
            "Concussion",
            "Whiplash",
            "Laceration",
            "Abrasions",
            "Internal bleeding"
        ];
        
        $builder
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'choices' => array_combine($types, $types),
             ])
             ->add('incident', EntityType::class, [
                'class' => 'App\Entity\Incident',
                'choice_label' => function (Incident $incident) {
                    // Return the combined label using multiple properties
                    return $incident->getType() . ' - ' . $incident->getDescription();
                },
                'choice_value' => 'incidentid', // Use 'id' as the value
                'placeholder' => 'Select an incident',
                'required' => true,
            ])                  
            ->add('numberPers', IntegerType::class, [
                'label' => 'Number of Persons',
                'required' => true,
            ])
            ->add('severity', RangeType::class, [
                'label' => 'Severity',
                'attr' => [
                    'min' => 1,
                    'max' => 10, // You can adjust the range as needed
                    'step' => 1, // The increment step
                ],
            ])
            ->add('severityLevel', HiddenType::class, [
                'mapped' => false, // This field won't be mapped to an entity property
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Injury::class,
        ]);
    }
}
