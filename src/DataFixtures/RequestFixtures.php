<?php

namespace App\DataFixtures;


use App\Entity\Request;
use App\Entity\Ticket;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class RequestFixtures extends BaseFixtures
{

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(100, 'main_Request', function($i) use ($manager) {

            $ticket=new Request();

            $ticket->setCreatedAt(new \DateTime());
            $ticket->setPrice(20);




//            $ticket->setCustomer($this->getRandomReference('main_customer'));



            return $ticket;

        });
        $manager->flush();
    }
//    public function getDependencies()
//    {
//        return [CustomerFixtures::class,
//        ];
//    }
}
