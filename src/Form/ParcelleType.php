<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Parcelle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ParcelleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // <label .. />
            // <input type="number" placeholder="Définir un nombre" class="input">
            ->add('number', IntegerType::class, [
                'label' => "Numéro de la parcelle",
                'attr' => [
                    'placeholder' => "Définir un nombre",
                ],
                'required' => true, // true est par defaut, donc pas besoin de mettre required
            ])
            ->add('size', NumberType::class, [
                'label' => "Superficie de la parcelle",
                'attr' => [
                    'placeholder' => "Définir une superficie pour la parcelle",
                ],
            ])
            ->add('owner', EntityType::class, [
                'class' => User::class,
                // Définie la value de l'option du select, à la soumission du formulaire, c'est cette valeur qui est envoyé au backend
                // L'identifiant de la ressource est l'id, pas le nom ou le prénom
                'choice_value' => 'id', 
                'label' => "Nom du propriétaire de la parcelle",
                'placeholder' => "Définir un nom de propriétaire pour la parcelle",
                //  Pour ne pas être obligé de renseigner un nom de propriétaire lors de la modification d'une parcelle
                'required' => false, 
            ])
        ;
    }

    // options du formulaire
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection'=>false,
            'data_class' => Parcelle::class,
            // Ajouter la class .form à la balise form (<form class="form">)
            'attr' => [
                'class' => 'form'
            ],
        ]);
    }
}
