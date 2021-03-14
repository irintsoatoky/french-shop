<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;



// use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;





class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add('index','detail'); // index = route
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('createAt','Commande passée le'),
            TextField::new('user.getFullName','Prénom Nom'),
            MoneyField::new('total')->setCurrency('EUR'), // Le total ne tient pas compte des frais de port : à voir...
            TextField::new('carrierName', 'Transporteur'),
            MoneyField::new('carrierPrice', 'Frais de port')->setCurrency('EUR'), // Le total ne tient pas compte des frais de port : à voir...
    
            BooleanField::new('isPaid'),
            ArrayField::new('orderDetails','Produits achetés')->hideOnIndex()
            // TextEditorField::new('description'),
        ];
    }
    
}
