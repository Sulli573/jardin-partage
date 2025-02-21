<?php

namespace App\Form;

use App\Entity\Parcelle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
            ->add('owner', TextType::class, [
                'label' => "Nom du propriétaire de la parcelle",
                'attr' => [
                    'placeholder' => "Définir un nom de propriétaire pour la parcelle",
                ]
            ])
        ;
    }

    // options du formulaire
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Parcelle::class,
        ]);
    }
}
