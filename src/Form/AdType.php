<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends AbstractType
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
            ->add('titre',TextType::class,$this->getConfiguration("Titre","Tapez un super titre pour votre annonce")
            )
            ->add('slug',TextType::class,
                $this->getConfiguration("Adresse web","Tapez l'adresse web (automatique",[
                    'required'=>false
                ]))
            ->add('price',MoneyType::class,$this->getConfiguration("Prix par nuit",
                "Indiquez le prix que vous voulez pour une nuit"))
            ->add('introduction',TextType::class,$this->getConfiguration("Introduction",
                "Donnez une description globale de l'annonce"))

            ->add('contente',TextareaType::class, $this->getConfiguration("Description
            detaillée","Tapez une description qui donne vraiment envie de venir chez vous !"))

            ->add('coverImage',UrlType::class,$this->getConfiguration("URL de l'image
            pricipale","Donnez l'adresse d'une image qui donne vraiment envie"))
            ->add('rooms',IntegerType::class,$this->getConfiguration("Nombre de chambres",
                "le nombre de chambre disponibles"))
            ->add('images',
            CollectionType::class,[

                'entry_type'=>ImageType::class,
                    'allow_add'=> true
                ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
