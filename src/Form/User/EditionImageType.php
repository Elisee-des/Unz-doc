<?php

namespace App\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EditionImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('image', FileType::class, [
            "mapped" => false,
            "label"=> "Votre image",
            "attr" => [
                "class" => "form-control"
            ],
            "constraints" => [
                new File([
                    "maxSize" => "2M",
                    "mimeTypes" =>[
                        "image/jpeg",
                        "image/png",
                        "image/bmp",
                        "image/pjpeg",
                    ]
                ])
            ]
        ])
            ->add('Modifier', SubmitType::class, [
                "attr" => [
                    "class" => "btn btn-outline-primary btn btn-sm",
                    "onclick" => "return confirm('Etes vous sûr de vouloir modifier votre image de profil ?')"
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
