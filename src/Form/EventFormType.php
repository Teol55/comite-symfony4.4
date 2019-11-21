<?php

namespace App\Form;

use App\Entity\Event;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class EventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Event|null $event */
        $event = $options['data'] ?? null;
        $isEdit = $event&& $event->getId();
        $builder
            ->add('name',TextType::class)
            ->add('description',null,[
                'label'=> 'Description du billet:'
            ])

            ->add('type',TextType::class,[
                'label'=> 'Type d\'évenement:'
            ])

        ;
        $imageConstraints=[new Image([
            'maxSize' => '5M'
        ])];
        if (!$isEdit || !$event->getImage()) {
            $imageConstraints[] = new NotNull([
                'message' => 'Il faut ajouter une image pour le Billet',
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
            'data_class' => Event::class,
        ]);
    }
}
