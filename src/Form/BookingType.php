<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingType extends AbstractType
{
    private $transformer;
    public function __construct(FrenchToDateTimeTransformer $transformer){
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate',TextType::class, [
                'label'=>"date d'arrivée",
                'attr' => [
                    'placeholder' => "la date à laquelle vous comptez arriver"]
                
               
                
            ])
            ->add('endDate',TextType::class, [
                'label'=>"date de départ",
                'attr' => [
                    'placeholder' => "la date à laquelle vous quittrz les lieux"]
                
                
            ])
            ->add('comment',TextareaType::class, [
                'label'=>false,
                'required'=>false
              

            ])
         
        ;
        $builder->get('startDate')->addModelTransformer($this->transformer);
        $builder->get('endDate')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
