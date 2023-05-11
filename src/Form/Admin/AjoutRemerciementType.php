<?php

namespace App\Form\Admin;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AjoutRemerciementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', EntityType::class, [
                "mapped" => false,
                "required" => true,
                "class"=>User::class,
                "label"=>"Choisir la personne a rémercier",
            ])
            ->add('description', TextareaType::class, [
                "mapped" => false,
                "required" => true,
                "label"=>"Entrez la raison du remerciement",
            ])
            ->add('Ajout', SubmitType::class, [
                "label" => "Ajout cette personne",
                "attr" => [
                    "class"=>"btn btn-outline-primary",
                    "onclick" => "return confirm('Etes vous sûr de vouloir ajouter cette personne a la liste des persnne a remercié ?')"
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
