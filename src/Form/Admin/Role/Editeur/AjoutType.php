<?php

namespace App\Form\Admin\Role\Editeur;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AjoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('numero', NumberType::class, [
            "label" => "Numéro de telephone",
        ])
        ->add('password', PasswordType::class, [
            "label" => "Entrez un mot de passe",
            'attr' => [
                "class" => "form-control mb-1",
                'autocomplete' => 'new-password',
                "placeholder" => "Il sera utilisable que sur cette plateforme."
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer un mot de passe svp',
                ]),
                new Length([
                    'min' => 1,
                    'minMessage' => 'Votre mot de passe doit atteindre {{ limit }} chiffres',
                    'max' => 8,
                    'maxMessage' => 'Votre mot de passe ne doit pas depassé {{ limit }} chiffres',
                ]),
            ],
            ])
        ->add('nomPrenom', TextType::class, [
            "label" => "Entrez le nom prènom",
        ])
        ->add('email', EmailType::class, [
            "label" => "Entrez l'email'",
        ])
        ->add('ine', TextType::class, [
            "label" => "Entrez l'ine'",
        ])
        ->add('motSecret', TextType::class, [
            "label" => "Entrez un mot secret",
        ])
        ->add('image', FileType::class, [
            "mapped" => false,
            "required" => false,
            "label" => "Une photo de l'editeur",
            "attr" => [
                "class" => "form-control mb-1",
            ],
            "constraints" => [
                new File([
                    "maxSize" => "5M",
                    "mimeTypes" =>[
                        "image/jpeg",
                        "image/png",
                        "image/bmp",
                        "image/pjpeg",
                        "image/x-jps"
                    ]
                ])
            ]
        ])
        ->add('filiere', TextType::class, [
            "label" => "Entrez la filière",
        ])
        ->add('specialite', TextType::class, [
            "label" => "Entrez la spécialité",
        ])
        ->add('annee', TextType::class, [
            "label" => "De quel promotion êtes vous ?",
        ])
        ->add('creer', SubmitType::class, [
            "label" => "Créer l'editeur",
            "attr" => [
                "class"=>"btn btn-outline-primary",
                "onclick" => "return confirm('Etes vous sûr de vouloir valider la création de cet editeur ?')"
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
