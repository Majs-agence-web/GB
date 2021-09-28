<?php

namespace App\Form;

use App\Form\UserType;
use App\Entity\ExperienceVisMaVie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ExperienceVisMaVie1Type extends AbstractType
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
            ->add('telephone')
            ->add('dateDebut', DateType::class, ["widget" => "single_text"])
            ->add('DateFin', DateType::class, ["widget" => "single_text"])
            // ->add('createdAt')
            // ->add('slug')
            // ->add('User', UserType::class)
            // ->add('metier')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExperienceVisMaVie::class,
        ]);
    }
}
