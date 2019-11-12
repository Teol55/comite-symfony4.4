<?php

namespace App\Form;

use App\Entity\Pvce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class PvceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Pvce|null $pvce */
        $pvce = $options['data'] ?? null;
        $isEdit = $pvce && $pvce->getId();

        $builder
            ->add('title')


        ;
        $pdfConstraints=[new File([
            'mimeTypes' => [
                'application/pdf',
                'application/x-pdf',
            ],
            'mimeTypesMessage'=> ' Merci de rentrer un fichier PDF valide'
        ])];
        if (!$isEdit || !$pvce->getPdfFilename()) {
            $pdfConstraints[] = new NotNull([
                'message' => 'Il faut ajouter un fichier PDF pour le PV',
            ]);}
        $builder
            ->add('pdfFile', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => $pdfConstraints
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pvce::class,
        ]);
    }
}
