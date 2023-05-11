<?php

namespace App\Form\Admin\Compte;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditionMotSecretType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('motSecret', TextType::class, [
                "label" => "Votre nouveau mot secret"
            ])
            ->add('Modifier', SubmitType::class, [
                "attr" => [
                    "class" => "btn btn-outline-primary",
                    "onclick" => "return confirm('Etes vous sûr de vouloir modifier votre mot secret ?')"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
