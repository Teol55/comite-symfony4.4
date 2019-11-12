<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Article|null $article */
        $article = $options['data'] ?? null;
        $isEdit = $article && $article->getId();

        $builder
            ->add('title', TextType::class,[
                'label' =>'Titre de l\'article:',
                'help'=> 'Titre de l\'article'
                     ])

            ->add('content',null,[
                'label' => 'Contenu de l\'article :'
                     ])
            ->add('publishedAt',DateType::class,[
                 'label'=>'Date de Publication:',
                 'widget' => 'single_text',
                        ])
            ->add('publishedEnd',DateType::class,[
                'label'=>'Fin de Publication:',
                'widget' => 'single_text',
                         ])

        ;
        $imageConstraints=[new Image([
            'maxSize' => '5M'
        ])];
        if (!$isEdit || !$article->getImageFilename()) {
            $imageConstraints[] = new NotNull([
                'message' => 'Il faut ajouter une image pour l\'article',
            ]);}
        $builder
            ->add('imageFile', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => $imageConstraints
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
