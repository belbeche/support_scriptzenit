<?php

namespace App\Form\Article\Type;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
                'required' => true,
                'attr' => [
                    'placeholder' => 'Titre',
                ],
            ])
            ->add('content', TextareaType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Contenu',
                ],
            ])
            /*->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'mapped' => false,
            ])*/
            ->add('categories',
                EntityType::class,
                [
                    'class'         => Category::class,
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('ca')
                            ->orderBy('ca.name', 'ASC');
                    },
                    'expanded' => true,
                    'multiple'      => true,
                    'choice_label'   => 'name',
                    'required'    => false,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}