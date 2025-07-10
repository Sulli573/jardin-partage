<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('message',TextareaType::class,[
                'label' => 'Message',
                // attr sur l'input
                'attr' => [
                    'rows' => 5,
                    'cols' => 100,
                    'minlength' =>5,
                    'maxlength' =>1000,
                ],
                // row attr sur le parent de l'input
                'row_attr' => ['class'=> 'post-form-input'
                ],
                'help' => 'Vous ne pouvez pas écrire plus de 1000 caractères',
                
            ])
            ->add('imageFile',VichImageType::class,[
                'label' => 'Image',
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // sur le formulaire
            'data_class' => Post::class,
        ]);
    }
}
