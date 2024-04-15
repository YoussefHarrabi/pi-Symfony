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
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class EquipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('relationTo', TextType::class, [
                'label' => 'Relation',
                'constraints' => [
                    new NotBlank(['message' => 'La relation est requise.']),
                ],
            ])
            ->add('nbrPersonne', IntegerType::class, [
                'label' => 'Nombre de personnes',
                'constraints' => [
                    new NotBlank(['message' => 'Le nombre de personnes est requis.']),
                    new Type(['type' => 'integer', 'message' => 'Le nombre de personnes doit être un entier.']),
                    new PositiveOrZero(['message' => 'Le nombre de personnes doit être positif ou zéro.']),
                ],
            ])
            ->add('work', EntityType::class, [
                'class' => Work::class,
                'choice_label' => 'location',
                'placeholder' => 'Sélectionner un travail',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Ajouter',
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
