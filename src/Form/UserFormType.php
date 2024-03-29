<?php

namespace App\Form;

use App\Entity\Roles;
use App\Entity\Users;
use App\Entity\Organization;
use App\Template\TemplateManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class, [
                'required' => true]);

        if (isset($options['role_name']) && TemplateManager::isRoleAdmin($options['role_name'])) {
			$builder
				->add('organizationName', EntityType::class,[
					'mapped'        => false,
					'required'      => true,
					'class'         => Organization::class,
					'choice_label'  => 'organizationName',
					'placeholder'   => 'Sélectionnez une organisation',
				])
				->add('roleName', EntityType::class, [
				'mapped'        => false,
				'required'      => true,
				'class'         => Roles::class,
				'choice_label'  => 'roleName',
				'placeholder'   => 'Sélectionnez un rôle',
				'constraints' => [
					new NotBlank([
						'message' => 'Veuillez choisir le rôle de l\'utilisateur.',
					])
				]
			]);
        }
		
        $builder
            ->add('agreeTerms', CheckboxType::class, [
            'mapped' => false,
            'constraints' => [
                new IsTrue([
                    'message' => 'Veuillez lire et accepter les conditions générale d\'utilisation.',
                ]),
            ],
        ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
            'role_name'  => null,
        ]);
    }
}
