<?php

namespace App\Form\User;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class EditionMotSecretType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('motSecret', RepeatedType::class, [
                "type" => TextType::class,
                "first_options" => [
                    "label" => "Entre votre nouveau mot secret",
                    'attr' => [
                        "class" => "form-control"
                    ]
                ],
                "second_options" => [
                    "label" => "Entre votre nouveau mot secret",
                    'attr' => [
                        "class" => "form-control"
                    ]
                ],
                "invalid_message" => "Ils doivent être identique.",
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
            ->add('Modifier', SubmitType::class, [
                "attr" => [
                    "class" => "btn btn-outline-primary",
                    "onclick" => "return confirm('Etes vous sûr de vouloir modifier votre mot secret ?')"
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
