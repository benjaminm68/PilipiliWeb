<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('description',
            TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'my-2'
                ]
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Prix',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('enabled', IntegerType::class, [
                'label' => 'Visible',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // ->add('slug', TextType::class, [
            //     'label' => 'Slug',
            //     'attr' => [
            //         'class' => 'form-control'
            //     ]
            // ])
            // ->add('created_at', DateType::class, [
            //     'label' => 'Date de création',
            //     'attr' => [
            //         'class' => 'form-control'
            //     ]
            // ])
            ->add('brand_id',EntityType::class, [
                'label' => 'Marque',
                'class' => Brand::class,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('valider',SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary btn-sm my-2'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
