<?php

namespace App\Form;

use App\Entity\Parcelle;
use App\Entity\User;
use App\Enum\UserRoleEnum;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function __construct(private UserRoleEnum $userRoleEnum)
    {
        
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class,[
                'label'=>"Rôles",
                'choices'=>array_flip($this->userRoleEnum->getRoles()),
                'multiple'=>true,
                'expanded'=>true,
            ])
            ->add('lastname')
            ->add('firstname')
            ->add('parcelle', EntityType::class, [
                'class' => Parcelle::class,
                // 'choice_label' => '',
                #p est la table parcelle, p.ower : left join avec la table user where id de la table user est null
                'query_builder'=> function(EntityRepository $er){
                    return $er->createQueryBuilder("p")
                    ->leftJoin('p.owner', 'u') 
                    ->where('u.id IS NULL');
                        
                        
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'roles'=>[],
        ]);
    }
  
}
