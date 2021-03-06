<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordUpdateType extends AbstractType
{

    /**
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array[]
     */
    private function getConfiguration($label,$placeholder,$options = []){
        return array_merge([
            'label'=>$label,
            'attr'=>[
                'placeholder'=>$placeholder
            ]
        ],$options);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword',PasswordType::class,$this->getConfiguration("Ancien mot de passe",
            "donnez votre mot de passe actuel"))
            ->add('newPassword',PasswordType::class,$this->getConfiguration("Nouveau mot de passe",
            "Tapez votre nouveau mot de passe"))
            ->add('confirmPassword',PasswordType::class,$this->getConfiguration("confirmation du mot de passe",
            "Confirmez votre nouveau mot de passe"));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
