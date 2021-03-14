<?php

namespace App\Controller\Admin;

use App\Entity\Header;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;


class HeaderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Header::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        // phpinfo(); // Reglage des max d'upload...
        return [
            TextField::new('title','Titre du header'),
            TextareaField::new('content', 'Contenu du header'),
            TextField::new('btnTitle','Titre du bouton'),
            TextField::new('btnUrl','Url de destination du bouton'),
            ImageField::new('illustration')
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads/')
                ->setUploadedFileNamePattern('header-[randomhash].[extension]'),
            ChoiceField::new('backgroundPosition', "Alignement de l'image")
                    ->autocomplete()
                    ->setChoices([  'Centré' => 'center',
                                    'Haut' => 'top',
                                    'Bas' => 'bottom',
                                    'A droite' => 'right',
                                    'A gauche' => 'left'
                                    ]
                                )
                  

            
                
        ];
    }
    
}
