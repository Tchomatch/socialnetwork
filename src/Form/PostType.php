<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('image', FileType::class, [
                
            'label' => 'Image (fichier)',                // non mappé signifie que ce champ n'est associé à aucune propriété d'entité
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
                        'image/jpeg',
                    ],
                    'mimeTypesMessage' => 'Veuillez importer un fichier .jpeg, .gif ou .png',
                ])
            ],
        
        ])
        ->add('contenu')
        ->add('Enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
