<?php

namespace App\Form;

use \App\Entity\Work;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;


class AjoutFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('location', TextType::class, [
                'label' => 'Location',
                'required' => true,
                'attr' => [
                    'class' => 'autocomplete-location', // Ajouter une classe pour cibler le champ avec JavaScript
                ],
            ])
            ->add('startdate', DateType::class, [
                'widget' => 'single_text',
                'required' => true,

            ])
            ->add('enddate', DateType::class, [
                'widget' => 'single_text',
                'required' => true,



            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,

            ])
            ->add('image', FileType::class, [
                'label' => 'Image',
                'required' => true,
                'mapped' => false, // Set mapped to false
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please upload an image file',
                    ]),
                ],
            ])

            ->add('isactive', CheckboxType::class, [
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Create',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Work::class,
        ]);
    }
}
