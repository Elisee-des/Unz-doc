<?php

namespace App\Form\Admin\Role\Admin;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditionPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero')
            ->add('roles')
            ->add('password')
            ->add('nomPrenom')
            ->add('email')
            ->add('ine')
            ->add('motSecret')
            ->add('photo')
            ->add('remerciement')
            ->add('dateCreation')
            ->add('photoNom')
            ->add('sauvegardeDuMotDePasse')
            ->add('filiere')
            ->add('specialite')
            ->add('annee')
            ->add('descriptionRemerciement')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
