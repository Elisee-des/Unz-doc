<?php

namespace App\Form\User;

use App\Entity\Annee;
use App\Entity\Examen;
use App\Entity\Module;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('module', EntityType::class, [
                "class" => Module::class,
                "label" => "Choisir le module",
                "attr" => [
                    "class" => "form-control",
                ],
            ])

            ->add('Rechercher', SubmitType::class, [
                "attr" => [
                    "class" => "btn btn-outline-primary btn btn-sm",
                    // "onclick" => "return confirm('Etes vous sûr de vouloir modifier votre image de profil ?')"
                ],
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
