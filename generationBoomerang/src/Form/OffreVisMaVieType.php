<?php

namespace App\Form;

use App\Form\UserType;
use App\Entity\OffreVisMaVie;
use App\Entity\ExperienceVisMaVie;
use App\Form\ExperienceVisMaVieType;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class OffreVisMaVieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descriptionMotivation')
            ->add('CvFile', FileType::class)
            ->add('user', UserType::class)
            ->add('telephone')
            // ->add('VisMaVie', ExperienceVisMaVieType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OffreVisMaVie::class,
        ]);
    }
}
