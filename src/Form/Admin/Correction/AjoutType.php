<?php

namespace App\Form\Admin\Correction;

use App\Entity\Correction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AjoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            "label" => "Entrez le nom de la session (*obliagtoire)",
        ])
        ->add('fichierPdf', FileType::class, [
            "required" => true,
            "mapped" => false,
            "label"=> "Attachez le fichier (*obliagtoire)",
            "attr" => [
                "class" => "form-control"
            ],
            "constraints" => [
                new File([
                    "maxSize" => "10M",
                    "mimeTypes" =>[
                        "application/pdf",
                    ]
                ])
            ]
        ])
            ->add('tailleFichier', TextType::class, [
                "required" => false,
                "label" => "Entrez la taille du fichier (*facultatif)",
                "attr" => [
                    "class" => "form-control",
                    "placeholder" => "Veuillez preciser la taille du fichier"
                ]
            ])
            ->add('remarque', TextareaType::class, [
                "required" => false,
                "label" => "Entrez la une remarque concernant",
                "attr" => [
                    "class" => "form-control",
                    "placeholder" => "Y'a t-il une remarque a faire sur ce fichier ?"
                ]
            ])
            ->add('Ajouter', SubmitType::class, [
                "label" => "Ajouter la correction",
                "attr" => [
                    "class"=>"btn btn-outline-primary", 
                    "onclick" => "return confirm('Etes vous sûr de vouloir ajouter ce fichier au archivre ?')"
                    ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Correction::class,
        ]);
    }
}
