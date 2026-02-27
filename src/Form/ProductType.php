<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Url;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Títol',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(message: 'El títol no pot estar buit'),
                    new Length(min: 3, max: 255, minMessage: 'El títol ha de tenir mínim {{ limit }} caràcters'),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descripció',
                'attr' => ['class' => 'form-control', 'rows' => 5],
                'constraints' => [
                    new NotBlank(message: 'La descripció no pot estar buida'),
                    new Length(min: 10, minMessage: 'La descripció ha de tenir mínim {{ limit }} caràcters'),
                ],
            ])
            ->add('price', NumberType::class, [
                'label' => 'Preu (€)',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(message: 'El preu no pot estar buit'),
                    new Positive(message: 'El preu ha de ser positiu'),
                ],
            ])
            ->add('image', UrlType::class, [
                'label' => 'URL de la imatge (opcional)',
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'https://...'],
                'constraints' => [
                    new Url(message: 'La URL de la imatge no és vàlida', requireTld: true),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
