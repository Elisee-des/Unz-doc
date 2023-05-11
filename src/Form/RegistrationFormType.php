<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero', NumberType::class, [
                "label" => "Veuillez entré un numéro de téléphone (*obligatoire)",
                "attr" => [
                    "class" => "form-control mb-1",
                    "placeholder" => "Entre un numéro de téléphone",
                    "maxlength" => 8
                ],
                'constraints' => [
                    new Length([
                        'min' => 8,
                        'minMessage' => "Votre numéro est incomplet.",
                    ]),
                    new Callback([
                        // Ici $value prend la valeur du champs que l'on est en train de valider, 
                        // ainsi, pour un champs de type TextType, elle sera de type string.
                        'callback' => static function (?string $value, ExecutionContextInterface $context) {
                            if (!$value) {
                                return;
                            }
                            // if (!\preg_match('~^\p{Lu}~u', $value)) {Pour savoir si ces majuscule ou pas
        
                            if (!\preg_match('/^[0]|^[5]|^[6]|^[7]/', $value)) {
                                //Seulement les mot commencant par eux
                                $context
                                    ->buildViolation('Le format de votre numéro est incorrect.')
                                    ->atPath('[numero]')
                                    ->addViolation()
                                ;
                            }
                        },
                    ]),
                ]
            ])
            ->add('password', PasswordType::class, [
                "label" => "Veuillez entré un mot de passe (*obligatoire)",
                'attr' => [
                    "class" => "form-control mb-1",
                    'autocomplete' => 'new-password',
                    "placeholder" => "Il sera utilisable que sur cette plateforme."
                ],
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'minMessage' => "Votre mot de passe doit faire au moins de 4 caractères",
                    ]),
                    new Callback([
                        // Ici $value prend la valeur du champs que l'on est en train de valider, 
                        // ainsi, pour un champs de type TextType, elle sera de type string.
                        'callback' => static function (?string $value, ExecutionContextInterface $context) {
                            if (!$value) {
                                return;
                            }
                            // if (!\preg_match('~^\p{Lu}~u', $value)) {Pour savoir si ces majuscule ou pas
        
                            if (!\preg_match('/[A-z0-9]+$/', $value)) {
                                $context
                                    ->buildViolation('Seuls les lettres et les chiffres sont autorisés')
                                    ->atPath('[password]')
                                    ->addViolation()
                                ;
                            }
                        },
                    ]),
                ]
                ])
                ->add('nomPrenom', TextType::class, [
                    "label" => "Veuillez entré votre nom et prenom (*obligatoire)",
                    "attr" => [
                        "class" => "form-control mb-1",
                        "placeholder" => "votre nom suivis de votre prenom"
                    ],
                    'constraints' => [
                        new Length([
                            'min' => 5,
                            'minMessage' => "Votre nom prénom doit faire au moins 5 caractères.",
                        ]),
                        new Callback([
                            // Ici $value prend la valeur du champs que l'on est en train de valider, 
                            // ainsi, pour un champs de type TextType, elle sera de type string.
                            'callback' => static function (?string $value, ExecutionContextInterface $context) {
                                if (!$value) {
                                    return;
                                }
                                // if (!\preg_match('~^\p{Lu}~u', $value)) {Pour savoir si ces majuscule ou pas
            
                                if (!\preg_match('/^[A-z. -]+$/', $value)) {
                                    //Seulement alphabet et space permit
                                    $context
                                        ->buildViolation('Seulement les lettres sont autorisées.')
                                        ->atPath('[nomPrenom]')
                                        ->addViolation()
                                    ;
                                }
                            },
                        ]),
                    ]
                ])
                ->add('email', EmailType::class, [
                    "required" => false,
                    "label" => "Veuillez entré votre email (*facultatif)",
                    "attr" => [
                        "class" => "form-control mb-1",
                    "placeholder" => "Ici votre email."
                    ]
                ])
                ->add('motSecret', TextType::class, [
                    "label" => "Veuillez entré un mot secret (*obligatoire)",
                    "attr" => [
                        "class" => "form-control mb-1",
                    "placeholder" => "Exple: UNZ000"
                    ],
                    'constraints' => [
                        new Length([
                            'min' => 4,
                            'minMessage' => "Votre mot secret doit faire moins de 4 caractères.",
                        ]),
                        new Callback([
                            // Ici $value prend la valeur du champs que l'on est en train de valider, 
                            // ainsi, pour un champs de type TextType, elle sera de type string.
                            'callback' => static function (?string $value, ExecutionContextInterface $context) {
                                if (!$value) {
                                    return;
                                }
                                // if (!\preg_match('~^\p{Lu}~u', $value)) {Pour savoir si ces majuscule ou pas
            
                                if (!\preg_match('/[A-z0-9]+$/', $value)) {
                                    $context
                                        ->buildViolation('Seuls les lettres et les chiffres sont autorisés.')
                                        ->atPath('[motSecret]')
                                        ->addViolation()
                                    ;
                                }
                            },
                        ]),
                    ]
                ]) 
                ->add('ine', TextType::class, [
                    "label" => "Veuillez entré votre INE (*obligatoire)",
                    "required" => true,
                    "attr" => [
                        "class" => "form-control mb-1",
                        "placeholder" => "Votre INE",
                        "maxlength" => 12

                    ],
                    'constraints' => [
                        new Length([
                            'min' => 12,
                            'minMessage' => "Votre INE est incomplet, veuillez verifier svp!",
                            'max' => 12,
                            'maxMessage' => "Votre INE est trop long, veuillez verifier svp!",
                        ]),
                        new Callback([
                            // Ici $value prend la valeur du champs que l'on est en train de valider, 
                            // ainsi, pour un champs de type TextType, elle sera de type string.
                            'callback' => static function (?string $value, ExecutionContextInterface $context) {
                                if (!$value) {
                                    return;
                                }
                                // if (!\preg_match('~^\p{Lu}~u', $value)) {Pour savoir si ces majuscule ou pas
            
                                if (!\preg_match('/N00|E00/', $value)) {
                                    //Doit commencer forcement par N00 ou E00
                                    $context
                                        ->buildViolation('Le format de votre INE est incorrect.')
                                        ->atPath('[ine]')
                                        ->addViolation()
                                    ;
                                }
                            },
                        ]),
                    ]
                ])
                ->add('filiere', TextType::class, [
                    "label" => "Veuillez entré votre filiere (*obligatoire)",
                    "required" => true,
                    "attr" => [
                        "class" => "form-control mb-1",
                        "placeholder" => "Votre filière"
                    ],
                    'constraints' => [
                        new Length([
                            'min' => 2,
                            'minMessage' => "Votre filière est doit comporter au moins 2 caractères",
                        ]),
                        new Callback([
                            // Ici $value prend la valeur du champs que l'on est en train de valider, 
                            // ainsi, pour un champs de type TextType, elle sera de type string.
                            'callback' => static function (?string $value, ExecutionContextInterface $context) {
                                if (!$value) {
                                    return;
                                }
                                // if (!\preg_match('~^\p{Lu}~u', $value)) {Pour savoir si ces majuscule ou pas
            
                                if (!\preg_match('/^[A-z]+$/', $value)) {
                                    //Seulement et seulement de Aa a Bb sont autorisé 
                                    $context
                                        ->buildViolation('Seules les lettres sont autorisées')
                                        ->atPath('[filiere]')
                                        ->addViolation()
                                    ;
                                }
                            },
                        ]),
                    ]
                ])
                ->add('specialite', TextType::class, [
                    "label" => "Veuillez entré votre spécialisation (*facultatif)",
                    "required" => false,
                    "attr" => [
                        "class" => "form-control mb-1",
                        "placeholder" => "Votre spécialisation"
                    ],
                    'constraints' => [
                        new Length([
                            'min' => 3,
                            'minMessage' => "Votre spécialité est doit comporter au moins 3 caractères.",
                        ]),
                        new Callback([
                            // Ici $value prend la valeur du champs que l'on est en train de valider, 
                            // ainsi, pour un champs de type TextType, elle sera de type string.
                            'callback' => static function (?string $value, ExecutionContextInterface $context) {
                                if (!$value) {
                                    return;
                                }
                                // if (!\preg_match('~^\p{Lu}~u', $value)) {Pour savoir si ces majuscule ou pas
            
                                if (!\preg_match('/^[A-z]+$/', $value)) {
                                    //Seulement et seulement de Aa a Bb sont autorisé 
                                    $context
                                        ->buildViolation('Seules les lettres sont autorisées.')
                                        ->atPath('[specialite]')
                                        ->addViolation()
                                    ;
                                }
                            },
                        ]),
                    ]
                ])
                ->add('annee', TextType::class, [
                    "label" => "De quel promotion êtes vous ?",
                    "attr" => [
                        "class" => "form-control mb-1",
                        "placeholder" => "Exple: P20",
                        "maxlength" => 4
                    ],
                    'constraints' => [
                        new Callback([
                            // Ici $value prend la valeur du champs que l'on est en train de valider, 
                            // ainsi, pour un champs de type TextType, elle sera de type string.
                            'callback' => static function (?string $value, ExecutionContextInterface $context) {
                                if (!$value) {
                                    return;
                                }
                                // if (!\preg_match('~^\p{Lu}~u', $value)) {Pour savoir si ces majuscule ou pas
            
                                if (!\preg_match('/^[0-9]+$/', $value)) {
                                    //Seulement et seulement de Aa a Bb sont autorisé 
                                    $context
                                        ->buildViolation('Seuls les chiffres sont autorisés.')
                                        ->atPath('[annee]')
                                        ->addViolation()
                                    ;
                                }
                            },
                        ]),
                    ]
                ])
                ->add('image', FileType::class, [
                    "mapped" => false,
                    "required" => false,
                    "label" => "Votre photo (*facultatif)",
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
                
                ->add('agreeTerms', CheckboxType::class, [
                    "label" => "J'accepte de me soumettre aux règles (*obligatoire)",
                    'mapped' => false,
                    'constraints' => [
                        new IsTrue([
                            'message' => 'Vous devez cocher cette case, elle est obligatoire',
                        ]),
                    ],
                ])

                // ->add('inscription', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
