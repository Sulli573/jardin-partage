<?php

namespace App\Form\Type;

use App\Service\AltchaService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class AltchaType extends AbstractType
{
    private AltchaService $altchaService;
    public function __construct(AltchaService $altchaService){

        $this->altchaService = $altchaService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('altcha_token', HiddenType::class,[
                'mapped'=>false,
                'attr'=>[
                    'class'=>'altcha-token'
                ],
                'constaints'=>[
                    new Callback([$this,'validateAltchaToken'])
                ]
            ]);
    }

    public function validateAltchaToken($value, ExecutionContextInterface $context) {
        if (empty($value)) {
            $context->buildViolation("Veuillez prouver que vous n'êtes pas un robot")->addViolation();
            return;
        }
        if (!$this->altchaService->verifyChallenge($value)) {
            $context->buildViolation("Vérification anti-robot échouée, veuillez rééssayer")->addViolation();
            return;
        }
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'error_bubbling'=> false
        ]);
    }
}
