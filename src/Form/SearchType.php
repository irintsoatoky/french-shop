<?php
namespace App\Form;

use App\Classe\Search;

use App\Entity\Category;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{// traduction des msg de validation dans config > packages > translation.yaml > defaut_local: fr
		 $builder
			  ->add('string', TextType::class,[
					'label'=> false,
					'required' => false,
					'attr' => [
						'placeHolder' => 'Votre recherche...',
						'class' => 'form-control-sm'
					]
			  ])
			  ->add('categories', EntityType::class,[ // categories reflete la class entity
					'class' => Category::class,
					'label'=> false,
					'required'=> false,
					'multiple'=> true, // possibiltié de plusieurs sélections
					'expanded' => true,
					'attr' => ['placeHolder' => 'Veuiller saisir votre email.']
			  ])
			  ->add('submit', SubmitType::class,[
				  'label' => 'Filtrer',
					'attr' => ['class'=> 'btn btn-info float-left']
				])
		 ;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		 $resolver->setDefaults([
			  'data_class' => Search::class,
			  'method' => 'GET',
			  'crsf' => false // Pas de protection crsf
		 ]);
	}
	public function getBlockPrefix()
	{
		// Source : https://www.udemy.com/course/apprendre-symfony-par-la-creation-dun-site-ecommerce/learn/lecture/22226914#questions
		// return parent::getBlockPrefix(); // Ajoute le nom de la class en prefix
		// TODO change the generate stub => ref : V35.12'01"
		return "";// Ajoute "" càd rien
	}
}

?>