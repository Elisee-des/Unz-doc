<?php

namespace App\Form\Admin\Compte;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditionPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('password', RepeatedType::class, [
            "mapped" => false,
            "type" => PasswordType::class,
            "first_options" => [
                "label" => "Nouveau mot de passe (*obligatoire)",
                'attr' => [
                    'autocomplete' => 'new-password',
                    "class" => "form-control"
                ]
            ],
            "second_options" => [
                "label" => "Repeter le mot de passe (*obligatoire)",
                'attr' => [
                    'autocomplete' => 'new-password',
                    "class" => "form-control"
                ]
            ],
            "invalid_message" => "Mot de passe non identique",
        ])
        ->add('Modifier', SubmitType::class, [
            "attr" => [
                "class" => "btn btn-outline-primary",
                "onclick" => "return confirm('Etes vous sûr de vouloir modifier votre mot de passe actuel ?')"
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
