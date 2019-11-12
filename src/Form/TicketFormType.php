<?php

namespace App\Form;

use App\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class TicketFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Ticket|null $ticket */
        $ticket = $options['data'] ?? null;
        $isEdit = $ticket&& $ticket->getId();
        $builder
            ->add('title',TextType::class)
            ->add('description',null,[
                'label'=> 'Description du billet:'
            ])

            ->add('priceCE',TextType::class,[
                'label'=> 'Prix du Billet:'
            ])
            ->add('url',UrlType::class,[
                'label'=> 'Url du site:',

            ])
        ;
        $imageConstraints=[new Image([
            'maxSize' => '5M'
        ])];
        if (!$isEdit || !$ticket->getImage()) {
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
            'data_class' => Ticket::class,
        ]);
    }
}
