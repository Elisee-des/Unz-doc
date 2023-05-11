<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NouveauMotDePasseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
        ->add('password', RepeatedType::class, [
            "type" => PasswordType::class,
            "first_options" => [
                "label" => "Nouveau mot de passe",
                'attr' => [
                    'autocomplete' => 'new-password',
                    "class" => "form-control mb-2"
                ]
            ],
            "second_options" => [
                "label" => "Repeter le mot de passe ",
                'attr' => [
                    'autocomplete' => 'new-password',
                    "class" => "form-control mb-2"
                ]
            ],
            "invalid_message" => "Mot de passe non identique",
        ])
        ->add('Modifier', SubmitType::class, [
            "label" => "Changer le mot de passe",
                'attr' => [
                    "class" => "form-control mb-2 col-12 btn-book-a-table w-100"
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
