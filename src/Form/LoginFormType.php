<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use VictorPrdh\RecaptchaBundle\Form\ReCaptchaType;

use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('recaptcha', ReCaptchaType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // Set default options for the form
        $resolver->setDefaults([
            'email_value' => null, // Default email value
        ]);
    }
}