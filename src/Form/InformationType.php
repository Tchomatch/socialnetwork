<?php

namespace App\Form;

use App\Entity\Information;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class InformationType extends AbstractType
{
    // fonction de création du formulaire de la table information
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // récupération des propriétés de la table information
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('date_naissance', BirthdayType::class, [
                // détermination du format de la date
                'format' => 'dd-MM-yyyy',
            ])
            ->add('adresse')
            ->add('ville')
            ->add('cp')
            ->add('description')

            //J'ajoute un bouton pour valider mon f
            ->add('modifier', SubmitType::class, [
                // je modifie sa couleur de base
                'attr' => ['class' => 'btn-warning'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Information::class,
        ]);
    }
}
