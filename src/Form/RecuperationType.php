<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RecuperationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nomPrenom', TextType::class, [
            "label" => "Nom Prenom",
            "attr" => [
                "class" => "form-control mb-3 (*obligation)",
                "placeholder" => "Celui utilisé lors de votre inscription"
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
            ->add('numero', NumberType::class, [
                "label" => "Veuillez entré votre mot numéro",
                "attr" => [
                    "class" => "form-control mb-3 (*obligation)",
                    "placeholder" => "Celui utilisé lors de votre inscription",
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
            ->add('motSecret', TextType::class, [
                "label" => "Veuillez entré votre mot Secret",
                "attr" => [
                    "class" => "form-control mb-3 (*obligation)",
                    "placeholder" => "Saisir le mot secret entre lors de l'inscritption"
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
            ->add('recuperation', SubmitType::class, [
                "label" => "Demander a changer mon mot de passe",
                    'attr' => [
                        "class" => "form-control mb-2 col-12 btn-book-a-table w-100"
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
