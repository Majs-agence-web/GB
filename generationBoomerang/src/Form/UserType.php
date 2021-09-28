<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('filename')
            // ->add('updated_at')
            ->add('email')
            // ->add('roles')
            // ->add('password')
            ->add('firstName')
            ->add('lastName')
            // ->add('description')
            // ->add('activation_token')
            // ->add('reset_token')
            // ->add('Adresse')
            // ->add('entreprise')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
