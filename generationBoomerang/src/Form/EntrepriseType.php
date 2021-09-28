<?php

namespace App\Form;

use App\Form\AdresseType;

use App\Entity\Entreprise;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntrepriseType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, $this->getConfiguration("Nom de l'entreprise", "Veuillez entrer nom de l'entreprise ..."))
            ->add('activite', TextType::class, $this->getConfiguration("Activité de l'entreprise", "Activité de l'entreprise ..."))
            ->add('Adresse' , AdresseType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
        ]);
    }
}
