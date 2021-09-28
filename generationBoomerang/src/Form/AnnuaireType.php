<?php

namespace App\Form;

use App\Entity\Annuaire;
use App\Form\FormationType;
use App\Form\FormulaireType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AnnuaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('fonction')
            // ->add('secteurActivite')
            // ->add('entreprise')
            // ->add('UserFormations', CollectionType::class, array(
            //     'entry_type' => FormulaireType::class
            // ));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annuaire::class,
        ]);
    }
}
