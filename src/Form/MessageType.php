<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contenu')
            ->add('image', FileType::class, [
                'multiple' => true,
                'label' => 'Image (fichier jpeg/png/gif/pdf)',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                // make it optional so you don't have to re-upload the PDF file
                // everytime you edit the Product details
                'required' => false,
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                // 'constraints' => [
                //     new File([
                //         'maxSize' => '10024k',
                //         'mimeTypes' =>[
                //             'image/jpeg',
                //             'image/jpg',                          
                //             'image/png',                       
                //             'image/gif',                           
                //             'image/pdf',
                      
                //        ],
                //         'mimeTypesMessage' => 'Veuillez importer un fichier jpg.'
                       
                        
                //    ])
                // ]
            
            ])
            ->add ('Envoyer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
