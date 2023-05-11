<?php

namespace App\Form\Admin\Role\Editeur;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AjoutFichierExcelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('fichier', FileType::class, [
            "label" => "Entrez le fichier excel",
            "constraints" => [
                new File([
                    "maxSize" => "2M",
                    "mimeTypes" => [
                        "application/vnd.oasis.opendocument.spreadsheet",
                        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    ]
                ])
            ]
        ])
        ->add('Importer', SubmitType::class, [
            "label" => "Importer le fichier excel",
            "attr" => [
                "class"=>"btn btn-danger",
                "onclick" => "return confirm('Etes vous sûr de vouloir ajouter cet fichier excel?')"
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
