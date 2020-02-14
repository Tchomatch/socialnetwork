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
            'mapped' => false,
            'label' => 'Mot de passe actuel'
            ))
            ->add('newPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Nouveau mot de passe'],
                'second_options' => ['label' => 'Répettez le mot de passe'],
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
                            ))
                            ;
                        }
                        
                        public function configureOptions(OptionsResolver $resolver)
                        {
                            $resolver->setDefaults([
                                'data_class' => User::class,
                                ]);
                            }
                        }
                        