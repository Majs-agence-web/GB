<?php

namespace App\Form;

use App\Form\MetierType;
use App\Form\AnnuaireType;
use App\Entity\ExperienceVisMaVie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ExperienceVisMaVieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('TitreAnnonce')
            ->add('description', TextareaType::class, [
                'attr' => ['rows' => '15'],
            ])
            ->add('nomEntreprise')
            ->add('villeEntreprise')
            ->add('dateDebut', DateType::class, ["widget" => "single_text"])
            ->add('DateFin',DateType::class, ["widget" => "single_text"])
            ->add('User')
            // ->add('metier', MetierType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExperienceVisMaVie::class,
        ]);
    }
}
