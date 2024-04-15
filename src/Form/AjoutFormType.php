<?php

namespace App\Form;

use App\Entity\Work;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;


class AjoutFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('location', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez saisir la localisation.'
                    ]),
                    new Assert\Length(['max' => 255]),
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
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez saisir une description.'
                    ]),
                    new Assert\Length(['max' => 65535]),
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
