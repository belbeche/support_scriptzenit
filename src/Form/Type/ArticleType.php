<?php

namespace App\Form\Type;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'annonce',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Titre de l\'article',
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu de l\'article',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Contenu de l\'article',
                ],
            ])
            ->add('createdAt', DateTimeType::class, [
                'label' => 'Veuillez choisir une date',
                'required' => true,
            ])
            ->add('Envoyer', SubmitType::class, [
                'label' => 'Création de votre annonce',
                'attr' => [
                    'class' => 'btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}