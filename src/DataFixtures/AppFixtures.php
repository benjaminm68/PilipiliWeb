<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Brand;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $brand = new Brand();
        $brand->setName('Adidas');
        $manager->persist($brand);
    
        $brand = new Brand();
        $brand->setName('Nike');
        $manager->persist($brand);
    
        $brand = new Brand();
        $brand->setName('Puma');
        $manager->persist($brand);
      
        $brand = new Brand();
        $brand->setName('Carhartt');        
        $manager->persist($brand);

        $manager->flush();

    }
}
