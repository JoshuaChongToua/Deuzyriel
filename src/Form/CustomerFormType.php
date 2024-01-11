<?php

namespace App\Form;


use App\Entity\Customers;
use App\Entity\Organization;
use App\Entity\CustomerPhysicals;
use App\Template\TemplateManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;


class CustomerFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('customerType', ChoiceType::class, [
                'placeholder' => 'Choisissez dans la liste',
                'choices' => [
                    'physique' => 'physical',
                    'moral' => 'moral'
                ],
            ])
            ->add('organization', ChoiceType::class, [
                'choices' => $options['organizations'],
                'placeholder' => 'Choisissez une organisation',
                'choice_label' => 'organizationName', // Propriété à afficher dans le champ de choix
                'choice_value' => 'id', // Propriété utilisée comme valeur de chaque option
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('email', TextType::class)
            ->add('address', TextType::class)
            ->add('zip', TextType::class)
            ->add('city', TextType::class)
            ->add('country', TextType::class)
            ->add('tel', TextType::class)
            ->add('isNPAI', ChoiceType::class, [
                'placeholder' => 'Choisissez si oui ou non',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'required' => true, //Ce champs est obligatoire
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customers::class,
            'role_name'  => null,
            'organizations' => null,
        ]);
    }
}
