<?php

namespace App\Form;

use App\Entity\Partner;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class PartnerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Partner|null $partner */
        $partner = $options['data'] ?? null;
        $isEdit = $partner && $partner->getId();
        $builder
            ->add('name',TextType::class,[
                'label'=> 'Nom du partenaire:'
            ])
            ->add('description',null,[
                'label'=> 'Description du Partenaire:'
            ])
            ->add('typeReduction',TextType::class,[
                'label'=> 'Inscrire le type de réduction:'
            ])
            ->add('url',UrlType::class,[
                'label'=> 'Url du site:',

            ])

        ;
        $imageConstraints=[new Image([
            'maxSize' => '5M'
        ])];
        if (!$isEdit || !$partner->getPathImage()) {
            $imageConstraints[] = new NotNull([
                'message' => 'Il faut ajouter une image pour le partenaire',
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
            'data_class' => Partner::class,
        ]);
    }
}
