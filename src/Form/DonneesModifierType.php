<?php

namespace App\Form;

use App\Entity\Donneeshistoriques;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DonneesModifierType extends DonneeshistoriquesType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        // Supprimer le champ idCapteur du formulaire de modification
        $builder->remove('idCapteur');
    }
}
