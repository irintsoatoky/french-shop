<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) // $options[] ?? => dd($options)
    {
        // dd($options);// on y trouve (en dernier) User passé en paramtètre dans le createForm(x,x, user) de OrderController
        
        // Récup de current user avec $options['user']
        $user = $options['user'];
        $builder
            ->add('addresses', EntityType::class,[
                'class' => Address::class,
                // 'label'=> 'Choisissez votre adresse de livraison', Supprimer pour pouvoir mettre Choisissez... + link (ajouter une addresse)
                'label' => false,
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'choices' => $user->getAddresses() // Sinon ceux sont les adresses de tous les users qui s'affichent
            ])
            ->add('carriers', EntityType::class,[
                'class' => Carrier::class,
                'label'=> 'Choisissez votre transporteur',
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                // 'choices' => $user->getCarriers() // Sinon ceux sont les adresses de tous les users qui s'affichent
            ])
			->add('submit', SubmitType::class,[
                'label' => 'Valider ma commande',
                'attr' => ['class' => "btn btn-success"]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'user' => array()
        ]);
    }
}
