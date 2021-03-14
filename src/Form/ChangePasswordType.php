<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class,[
                'label'=> 'Mon prénom',
                'disabled' => true
           ])
           ->add('lastname', TextType::class,[
                'label'=> 'Mon nom',
                'disabled' => true
           ])
           ->add('email', EmailType::class,[
                'label'=> 'Votre email',
                'disabled' => true
           ])           
           ->add('old_password', PasswordType::class,[
                'mapped' => false,
                'label'=> 'Saisir votre mot de passe actuel'
            ])
           ->add('new_password', RepeatedType::class,[// sert 2 deux fois( first_option, second_option)
               'type' => PasswordType::class,
               'mapped' => false,
               'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques.',
               'required' => true,
               'first_options' => ['label' => 'Nouveau mot de passe', 'attr' => ['placeHolder' => 'Saisir votre mot de passe.']],
               'second_options' => ['label' => 'Confirmer votre nouveau mot de passe', 'attr' => ['placeHolder' => 'Confirmer votre mot de passe.']]
         ])
           ->add('submit', SubmitType::class,['label' => 'Mettre à jour'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
