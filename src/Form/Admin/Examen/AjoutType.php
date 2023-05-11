<?php

namespace App\Form\Admin\Examen;

use App\Entity\Examen;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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
            "required" => false,
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
                "label" => "Entrez la taille du fichier (*obliagtoire)",
                "attr" => [
                    "class" => "form-control",
                    "placeholder" => "Veuillez saisir juste un chiffre"
                ]
            ])
            ->add('remarque', TextareaType::class, [
                "required" => false,
                "label" => "Entrez la une remarque s'il y'a lieu",
                "attr" => [
                    "class" => "form-control",
                    "placeholder" => "Y'a t-il une remarque a faire sur ce fichier ?"
                ]
            ])
            ->add('Ajouter', SubmitType::class, [
                "label" => "Ajouter l'examen",
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
            'data_class' => Examen::class,
        ]);
    }
}
