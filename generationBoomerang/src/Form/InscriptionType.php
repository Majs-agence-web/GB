<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Document;
use App\Form\DocumentType;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class InscriptionType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add(
            // $builder->create('documents', FormType::class, ['by_reference' => 'Document'])
            //     ->add('docFile', FileType::class)                
            // )
            ->add('telephone', TextType::class, $this->getConfiguration("Téléphone", "Votre numéro de téléphone..."))
            ->add('email',EmailType::class, $this->getConfiguration("Email", "Votre adresse email..."))
            // ->add('roles')
            ->add('password',PasswordType::class, $this->getConfiguration("Mot de passe", "Choisissez un mot de passe Robuste ..."))
            ->add('passwordConfirm',PasswordType::class, $this->getConfiguration("Confirmation mot de passe", "Veuillez retaper votre mot de passe..."))
            ->add('civilite',ChoiceType::class,[
                    'multiple'=>false,
                    'expanded'=>true,
                    'choices'=>[
                        'Madame'=>'Madame',
                        'Monsieur'=>'Monsieur'
                    ]
            ])
            ->add('firstName', TextType::class, $this->getConfiguration("Prénom", "Votre prénom ..."))
            ->add('lastName',TextType::class, $this->getConfiguration("nom", "Votre nom de Famille ..."))
            ->add('birthdate', BirthdayType::class, [
                'label' => 'Date de naissance',
                'format' => 'dd-MM-yyyy', 
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ]                  
                
            ])

            ->add('imageFile', FileType::class, [ 
                'required' => false                
                ] )
            ->add('description', TextareaType::class, $this->getConfiguration("Introduction", "Présentez vous en quelques mots ...",[
                'required' => false])
            )
            ->add('Adresse', AdresseType::class)
            
            ->add('documents', FileType::class ,[               
                'label' => false,
                'multiple' => true,
                'mapped' => false, 
                'required' => false              
                   
            ])

            ->add('Entreprise', EntrepriseType::class, [
                'required' => false,
                
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
