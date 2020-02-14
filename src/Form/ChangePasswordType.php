<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, array(
                // le champ old password ne correspond pas à un  champ en bdd, on lui donne alors un mapped = false pour lui signifier qu'il ne doit pas tenter d'enregistrer la donnée telle quelle
                'mapped' => false,
                'label' => 'Mot de passe actuel'
            ))
            // ici on donne au deuxième champ un RepeatedType pour que l'utilisateur soit contraint de taper son nouveau mot de passe deux fois avant de l'enregistrer (on évite ainsi les erreurs communes type faute de frappe)
            ->add('newPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Nouveau mot de passe'],
                'second_options' => ['label' => 'Répettez le mot de passe'],
                // insertion d'un message d'erreur si les deux champs ne sont pas identiques
                'invalid_message' => 'Les deux mots de passe doivent être identiques',
                'options' => array(
                    'attr' => array(
                        'class' => 'password-field'
                    )
                ),
                'required' => true,
                'mapped' => false
            ))
            ->add('envoyer', SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn btn-warning'
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
