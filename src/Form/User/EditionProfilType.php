<?php

namespace App\Form\User;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class EditionProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nomPrenom', TextType::class, [
            'constraints' => [
                new Length([
                    'min' => 5,
                    'minMessage' => "Votre nom prénom doit faire au moins 5 caractères",
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
                                ->buildViolation('Seulement les lettres sont autorisées')
                                ->atPath('[motSecret]')
                                ->addViolation()
                            ;
                        }
                    },
                ]),
            ]
        ])
            ->add('numero', NumberType::class, [
                "label" => "Votre numéro",
                "attr" => [
                    "class" => "form-control",
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
                                    ->atPath('[motSecret]')
                                    ->addViolation()
                                ;
                            }
                        },
                    ]),
                ]
            ])
            ->add('email', EmailType::class)
            ->add('ine', TextType::class, [
                "attr" => [
                    // "class" => "form-control",
                    "maxlength" => 12
                ],
                'constraints' => [
                    new Length([
                        'min' => 12,
                        'minMessage' => "Votre INE est incomplet, veuillez verifier svp!",
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
                                    ->atPath('[motSecret]')
                                    ->addViolation()
                                ;
                            }
                        },
                    ]),
                ]
            ])
            ->add('filiere', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'minMessage' => "Votre filière est doit comporter au moins 2 caractères.",
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
                                    ->atPath('[motSecret]')
                                    ->addViolation()
                                ;
                            }
                        },
                    ]),
                ]
            ])
            ->add('specialite', TextType::class, [
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
                                    ->atPath('[motSecret]')
                                    ->addViolation()
                                ;
                            }
                        },
                    ]),
                ]
            ])
            ->add('Editer', SubmitType::class, [
                "attr" => [
                    "class" => "btn btn-outline-primary",
                    "onclick" => "return confirm('Etes vous sûr de vouloir modifier les informations de votre profil ?')"
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
