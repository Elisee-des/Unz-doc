<?php

namespace App\Form\Admin\Compte;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditionProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero', NumberType::class)
            ->add('nomPrenom', TextType::class)
            ->add('email', EmailType::class)
            ->add('filiere', TextType::class)
            ->add('specialite', TextType::class)
            ->add('ine', TextType::class)
            ->add('Modifier', SubmitType::class, [
                "attr" => [
                    "class" => "btn btn-outline-primary",
                    "onclick" => "return confirm('Etes vous sûr de vouloir modifier les informations de votre profil ?')"
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
