<?php

namespace App\Form;

use App\Entity\User;
use App\Form\AdresseType;
use App\Form\ApplicationType;
use Liip\ImagineBundle\Form\Type\ImageType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;


class UpdateInscriptionType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('email',EmailType::class, $this->getConfiguration("EMAIL", "Votre adresse email..."))
            // ->add('roles')
            ->add('firstName',TextType::class, $this->getConfiguration("PRÉNOM", "Votre prénom ..."))
            ->add('lastName',TextType::class, $this->getConfiguration("NOM", "Votre nom de Famille ..."))
            
            //->add('description',TextType::class, $this->getConfiguration("INTRODUCTION", "Présentez vous en quelques mots ..."))
            ->add('Adresse',AdresseType::class)
            ->add('imageFile', FileType::class, [ 
                'required' => false
                
                ] )
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
