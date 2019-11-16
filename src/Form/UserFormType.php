<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class)
            ->add('forname',TextType::class,[
                'label'=>'Nom:'
            ])
            ->add('surname',TextType::class,[
                'label'=>'Prénom:'
            ])
             ->add('nbPersonne',IntegerType::class,[
                 'label'=>'Nombre de personne dans le foyer:',
                 'required'=>false
             ])
            ->add('isComite',CheckboxType::class,
                [
                    'label'=>'Membre du CSE',
                    'required'=>false
                ])
        ->add('syndicat',ChoiceType::class,[
        'label'=> 'Syndicat du membre du CSE:',
        'choices'=>['Syndicat C.G.T'=>'cgt','Syndicat C.F.E-C.G.C'=> 'cfe','Sans Etiquettes'=>'sans']])

            ->add('phoneNumber',TelType::class,[
            'label'=>"Numéro de téléphone"]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
