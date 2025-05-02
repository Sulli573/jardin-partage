<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'email',
                'row_attr' => [
                    'class' => "form-input",
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'row_attr' => [
                    'class' => "form-input",
                ]

            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'row_attr' => [
                    'class' => "form-input",
                ]

            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'invalid_message' => 'les mots de passe doivent correspondre',
                'first_options' => [
                    'label' => 'mot de passe',
                    'row_attr' => [
                        'class' => "form-input",
                    ],
                    'help' => '',
                    'constraints' => [
                        new Regex(
                            pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/',
                            message: "Veuillez créer un mot de passe d'au moins 12 caractères, incluant une majuscule, une minuscule, un chiffre et un caractère spécial (@$!%*?&)."
                        )
                    ],
                ],
                'second_options' => [

                    'label' => 'repeter le mot de passe',
                    'row_attr' => [
                        'class' => "form-input",
                    ]
                ],
                #Il n'y a pas de lien avec l'entité User, plainPassword n'est pas une propriété de User (logique dans RegistrationController.php)
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr'=>[
                'class'=>'form-registration'
            ]
        ]);
    }
}
