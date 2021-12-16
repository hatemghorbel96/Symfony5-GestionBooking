<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
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
                'placeholder'=>$placeholder,'class'=>'form-control',
            ]
        ],$options);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname' ,TextType::class,$this->getConfiguration("prenom","votre prenom"))
            ->add('lastName',TextType::class,$this->getConfiguration("nom","votre nom"))
            ->add('email',EmailType::class,$this->getConfiguration("email","votre email"))
            ->add('picture',UrlType::class,$this->getConfiguration("photo de profil","URL de votre avatar"))
            ->add('hash',PasswordType::class,$this->getConfiguration("mot de passe","choissisez un mot de passe"))
            ->add('passwordConfirm',PasswordType::class,$this->getConfiguration("confirmation de mot de passe","veuillez confirmez votre mot de passe"))
            ->add('tel',NumberType::class,$this->getConfiguration("Télephone","Votre numero de telephone"))
            ->add('introduction',TextareaType::class, [
          
                'label' => 'Introduction',
                'attr' => [
                    'class' => 'form-control', 'rows'=>3,'placeholder'=>'presentez vous'
                ]
            ])
            ->add('description',TextareaType::class, [
          
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control', 'rows'=>4,'placeholder'=>'cest le moment de vous presenter en details'
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
