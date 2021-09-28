<?php

namespace App\Form;

use App\Form\ImageType;


use App\Entity\Articles;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ArticlesType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titreArticle',TextType::class)
            ->add('accroche',TextType::class)
            ->add('intro',CKEditorType::class)
            ->add('contenuArticle',CKEditorType::class)
            ->add('images', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false, 
                'required' => false                
                ] )
            ->add('video',UrlType::class, [
                'required' => false                
                ] )
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
