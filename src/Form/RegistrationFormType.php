<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo*',
            ])
            ->add('email', TextType::class, [
                'label' => 'Email*',
            ])
            ->add('image', FileType::class, [
                
                'label' => 'Image (facultatif)',                // non mappé signifie que ce champ n'est associé à aucune propriété d'entité
                'mapped' => false,                // rendez-le facultatif pour ne pas avoir à télécharger à nouveau le fichier PDF
                // chaque fois que vous modifiez les détails du produit
                'required' => false,                // les champs non mappés ne peuvent pas définir leur validation à l'aide d'annotations
                // dans l'entité associée, vous pouvez donc utiliser les classes de contraintes PHP
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
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Accepter les conditions*',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.*',
                        
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Mot de passe*',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe.',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
