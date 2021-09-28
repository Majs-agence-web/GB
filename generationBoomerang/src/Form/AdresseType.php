<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdresseType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numeroRue', TextType::class, $this->getConfiguration("Numéro de la rue", "Veuillez entrer le numéro de votre rue ..."))
            ->add('nomRue', TextType::class, $this->getConfiguration("Nom de la rue", "Veuillez entrer le nom de votre rue ..."))
            ->add('codePostal', TextType::class, $this->getConfiguration("le code Postal", "Veuillez entrer le code postal de votre commune ..."))
            ->add('ville', TextType::class, $this->getConfiguration("ville", "Veuillez entrer le nom de votre commune ..."))
            ->add('region', TextType::class, $this->getConfiguration("la région", "Veuillez entrer votre région ..."))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
