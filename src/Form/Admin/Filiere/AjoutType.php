<?php

namespace App\Form\Admin\Filiere;

use App\Entity\Departement;
use App\Entity\Filiere;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class AjoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                "label" => "Entrez le nom la filière",
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'minMessage' => "Le nom de la filière doit faire au moins 4 caractères.",
                    ]),
                    new Callback([
                        // Ici $value prend la valeur du champs que l'on est en train de valider, 
                        // ainsi, pour un champs de type TextType, elle sera de type string.
                        'callback' => static function (?string $value, ExecutionContextInterface $context) {
                            if (!$value) {
                                return;
                            }
                            // if (!\preg_match('~^\p{Lu}~u', $value)) {Pour savoir si ces majuscule ou pas
        
                            // if (!\preg_match('/^[A-zéèêâ. -()]+$/', $value)) {
                            //     //Seulement alphabet et space permit
                            //     $context
                            //         ->buildViolation('Seulement les lettres sont autorisées.')
                            //         ->atPath('[nom]')
                            //         ->addViolation()
                            //     ;
                            // }
                        },
                    ]),
                ]
            ])
            // ->add('departement', EntityType::class, [
            //     "class" => Departement::class,
            //     "label" => "Entrez le  nom du département"
            // ])
            ->add('Creer', SubmitType::class, [
                "label" => "Créer la filière",
                "attr" => ["class"=>"btn btn-outline-primary"]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Filiere::class,
        ]);
    }
}
