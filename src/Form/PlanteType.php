<?php

namespace App\Form;

use App\Entity\Plante;
use App\Entity\Parcelle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class PlanteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la plante',
                #COntraintes côté client
                'attr' => [
                    'minLength' => 1,
                    'maxLength' => 40,
                ]
                #required est à true par défaut
            ])
            ->add('type', TextType::class, [
                'label' => 'Type de plante',
                'attr' => [
                    'minLength' => 1,
                    'maxLength' => 40,
                ]
            ])
            #DateTimeType va mettre date et heure DateType que la date
            ->add('datePlantation', DateType::class, [
                'label' => "Date de plantation",
                'widget' => 'single_text',
                'attr' => [
                    // penser à mettre coté serveur avec assert, pas possible de mettre des jours avant la date de plantation
                    'min' => (new \DateTime())->format('Y-m-d')
                ]
            ])
            ->add('periodeCroissance', IntegerType::class, [
                'label' => "Periode de croissance (jour)",
                // 'help' => "Saisir en nombre de jours"
                'attr' => [
                    'min' => 1,
                ]
            ])
           
            ->add('parcelle', EntityType::class, [
                'class' => Parcelle::class,
                'choice_label' => function (Parcelle $parcelle) {
                    return 'Numéro #' . $parcelle->getNumber();
                },
                'choice_value' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plante::class,
            // Ajouter la class .form à la balise form (<form class="form">)
            'attr' => [
                'class' => 'form'
            ],
        ]);
    }
}
