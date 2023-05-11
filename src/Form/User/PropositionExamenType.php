<?php

namespace App\Form\User;

use App\Entity\PropositionExamen;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PropositionExamenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomSession', TextType::class, [
                "label"=>"Session normale ou rattrapage ?"
            ])
            ->add('examenFichier', FileType::class, [
                "mapped" => false,
                "required" => true,
                "label" => "Attachez ici le fichier pdf de l'examen",
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
                "label" => "Proposé l'examen",
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
            'data_class' => PropositionExamen::class,
        ]);
    }
}
