<?php

namespace App\Form;

use App\Entity\Equipe;
use App\Entity\Work;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EquipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('relationTo', TextType::class, [
                'label' => 'Relation',
            ])
            ->add('nbrPersonne', IntegerType::class, [
                'label' => 'Nombre de personnes',
            ])
            ->add('work', EntityType::class, [
                'class' => Work::class,
                'choice_label' => 'location',
                'placeholder' => 'Sélectionner un travail',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipe::class,
        ]);
    }
}
