<?php

namespace App\Form;

use App\Form\AnnuaireType;
use App\Form\FormationType;
use App\Entity\UserFormation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FormulaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('anneePromo')
            // ->add('user')
            // ->add('formation')
            //->add('Annuaire', AnnuaireType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserFormation::class,
        ]);
    }
}
