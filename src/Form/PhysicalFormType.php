<?php

namespace App\Form;


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


class PhysicalFormType extends AbstractType
{
	
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('firstName', TextType::class)
			->add('lastName', TextType::class)
			->add('dateBirthday', BirthdayType::class)
			->add('gender', ChoiceType::class, [
				'placeholder' => 'Choisissez dans la liste',
				'choices'   => [
					'homme' => 'male',
					'femme' => 'female',
					'binaire' => 'non-binary',
					'autre'   => 'other'
				],
			]);


		
		if (isset($options['role_name']) && TemplateManager::isRoleAdmin($options['role_name'])) {
			$builder
				->add('organization', EntityType::class,[
					'mapped'        => false,
					'required'      => true,
					'class'         => Organization::class,
					'choice_label'  => 'organizationName',
					'placeholder'   => 'Sélectionnez une entitée',
					'constraints'   => [
						new NotBlank([
							'message' => 'Veuillez choisir une entitée parmis la liste proposée.',
						])
					]
				]);
		}
		;
	}
	
	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => CustomerPhysicals::class,
			'role_name'  => null,
            'organizations' => null,
		]);
	}
}
