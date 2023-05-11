<?php

namespace App\Form\Admin\Licence;

use App\Entity\Filiere;
use App\Entity\Licence;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class EditionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            "label" => "Entrez le nom de la licence",
            'constraints' => [
                new Length([
                    'min' => 5,
                    'minMessage' => "Le nom de la licence doit faire au moins 5 caractères.",
                ]),
                new Callback([
                    // Ici $value prend la valeur du champs que l'on est en train de valider, 
                    // ainsi, pour un champs de type TextType, elle sera de type string.
                    'callback' => static function (?string $value, ExecutionContextInterface $context) {
                        if (!$value) {
                            return;
                        }
                        // if (!\preg_match('~^\p{Lu}~u', $value)) {Pour savoir si ces majuscule ou pas
    
                        // if (!\preg_match('/^[A-z0-9. -]+$/', $value)) {
                        //     //Seulement alphabet et space permit
                        //     $context
                        //         ->buildViolation('Seulement les lettres et chiffres sont autorisées.')
                        //         ->atPath('[nom]')
                        //         ->addViolation()
                        //     ;
                        // }
                    },
                ]),
            ]
        ])
        // ->add('filiere', EntityType::class, [
        //     "class" => Filiere::class,
        //     "label" => "Entrez le  nom de la filière"
        // ])
        ->add('Modifier', SubmitType::class, [
            "label" => "Modifier la licence",
            "attr" => ["class"=>"btn btn-outline-primary"]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Licence::class,
        ]);
    }
}
