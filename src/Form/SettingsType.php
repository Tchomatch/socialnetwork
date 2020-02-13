<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('pseudo')
            ->add('image', FileType::class, [
                'label' => 'Image (facultatif)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/gif', 
                            'image/png',
                            'image/jpg', 
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Veuillez importer un fichier .jpeg, .gif ou .png',
                    ])
                ],
            
            ])
            // J'ajoute un bouton pour valider le formulaire
            ->add('modifier', SubmitType::class, [
                // je modifie sa couleur de base
                'attr' => ['class' => 'secondary'],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
