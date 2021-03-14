<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Validator\Constraints\Length;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class RegisterType extends AbstractType
{
	 public function buildForm(FormBuilderInterface $builder, array $options)
	 {// traduction des msg de validation dans config > packages > translation.yaml > defaut_local: fr
		  $builder
				->add('firstname', TextType::class,[
					 'label'=> 'Votre prénom',
					 // Source constraints : https://symfony.com/doc/current/reference/constraints.html
					 'constraints' => new Length(['min' => 2,'max' => 30]),// source Lenght : https://symfony.com/doc/current/reference/constraints/Length.html#basic-usage
					 'attr' => ['placeHolder' => 'Jean']
				])
				->add('lastname', TextType::class,[
					 'label'=> 'Votre nom',
					 'constraints' => new Length(['min' => 2,'max' => 30]),// source Lenght : https://symfony.com/doc/current/reference/constraints/Length.html#basic-usage
					 'attr' => ['placeHolder' => 'Dupont']
				])
				->add('email', EmailType::class,[
					 'label'=> 'Votre email',
					 'constraints' => new Length(['max' => 60]),// source Lenght : https://symfony.com/doc/current/reference/constraints/Length.html#basic-usage
					 'attr' => ['placeHolder' => 'Veuiller saisir votre email.']
				])
				// ->add('password', PasswordType::class,[
				// 	 'label'=> 'Votre mot de passe',
				// 	 'attr' => ['placeHolder' => 'Veuiller saisir votre mot de passe.']
				// ])
				// ->add('password_confirm', PasswordType::class,[
				// 	 'label'=> 'Confirmez votre mot de passe',
				// 	 'mapped' => false, // permet de ne pas déclarer inutilement password_confirm (=password)
				// 	 'attr' => ['placeHolder' => 'Resaisir votre mot de passe.']
				// ])

				// Autre façon de faire
				->add('password', RepeatedType::class,[// sert 2 deux fois( first_option, second_option)
					'type' => PasswordType::class,
					'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques.',
					'required' => true,
					'first_options' => ['label' => 'Mot de passe', 'attr' => ['placeHolder' => 'Saisir votre mot de passe.']],
					'second_options' => ['label' => 'Confirmer votre mot de passe', 'attr' => ['placeHolder' => 'Confirmer votre mot de passe.']]
			  ])
				->add('submit', SubmitType::class,['label' => 'S\'inscrire'])
		  ;
	 }

	 public function configureOptions(OptionsResolver $resolver)
	 {
		  $resolver->setDefaults([
				'data_class' => User::class,
		  ]);
	 }
}
