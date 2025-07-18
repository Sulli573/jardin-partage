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
                ],
                'row_attr' => [
                    'class' => 'plante-form-input'
                ],
                #required est à true par défaut
            ])
            ->add('type', TextType::class, [
                'label' => 'Type de plante',
                'attr' => [
                    'minLength' => 1,
                    'maxLength' => 40,
                ],
                'row_attr' => [
                    'class' => 'plante-form-input'
                ],
            ])
            #DateTimeType va mettre date et heure DateType que la date
            ->add('datePlantation', DateType::class, [
                'label' => "Date de plantation",
                'widget' => 'single_text',
                'attr' => [
                    // penser à mettre coté serveur avec assert, pas possible de mettre des jours avant la date de plantation
                    'min' => (new \DateTime())->format('Y-m-d')
                ],
                'row_attr' => [
                    'class' => 'plante-form-input'
                ],
            ])
            ->add('periodeCroissance', IntegerType::class, [
                'label' => "Periode de croissance (jour)",
                // 'help' => "Saisir en nombre de jours"
                'attr' => [
                    'min' => 1,
                ],
                'row_attr' => [
                    'class' => 'plante-form-input'
                ],
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plante::class,
        ]);
    }
}
