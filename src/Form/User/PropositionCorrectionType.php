<?php

namespace App\Form\User;

use App\Entity\PropositionCorrection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PropositionCorrectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nomSession', TextType::class, [
            "label"=>"Session normale ou rattrapage ?"
            ])
        ->add('description', TextType::class, [
            "label"=>"Sujet complet ou partiel ? Veuillez faire une petite description de votre correction"
        ])
        ->add('correctionFichier', FileType::class, [
            "mapped" => false,
            "required" => true,
            "label" => "Attachez ici le fichier pdf de la correction",
            "attr" => [
                "class" => "form-control mb-1",
            ],
            "constraints" => [
                new File([
                    "maxSize" => "5M",
                    "mimeTypes" =>[
                        "application/pdf",
                    ]
                ])
            ]
        ])
        ->add('Ajouter', SubmitType::class, [
            "label" => "Proposé une correction",
            "attr" => [
                "class"=>"btn btn-outline-primary", 
                "onclick" => "return confirm('Etes vous sûr de vouloir continuer ?')"
                ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PropositionCorrection::class,
        ]);
    }
}
