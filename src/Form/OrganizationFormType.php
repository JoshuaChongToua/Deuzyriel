<?php

namespace App\Form;


use App\Entity\Organization;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrganizationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('organizationName', TextType::class)
            ->add('siret', TextType::class)
            ->add('address', TextType::class)
            ->add('zip', TextType::class)
            ->add('country', TextType::class)
            ->add('tel', NumberType::class)
            ->add('type', TextType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Organization::class,
        ]);
    }
}
