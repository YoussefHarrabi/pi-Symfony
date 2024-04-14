<?php

namespace App\Form;

use App\Entity\Utilisateurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class UserRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('motDePasse', PasswordType::class, [
                'attr' => [
                    'class' => 'form-control input-lg',
                    'placeholder' => 'Password'
                ]
            ])
            ->add('age', DateType::class, [
                'widget' => 'single_text', // Use single_text widget for single text input
                'html5' => true, // Use HTML5 input type for native date picker
                'label' => 'Date de naissance', // Label for the field
                'attr' => [
                    'class' => 'form-control', // Add any custom classes here
                    'placeholder' => 'Select Date of Birth' // Placeholder text
                ]
            ]);
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateurs::class,
        ]);
    }
}
