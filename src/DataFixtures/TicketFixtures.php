<?php

namespace App\DataFixtures;


use App\Entity\Ticket;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class TicketFixtures extends BaseFixtures implements  DependentFixtureInterface
{

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(15, 'main_ticket', function($i) use ($manager) {

            $ticket=new Ticket();



            $ticket->setTitle($this->faker->Name());
            $ticket->setDescription($this->faker->text());
            $ticket->setImage('/images/');
            $ticket->setPrice(20);
            $ticket->setPriceCe(12);



           $ticket->setRequest($this->getRandomReference('main_Request'));



            return $ticket;

        });
        $manager->flush();
    }
    public function getDependencies()
    {
        return [RequestFixtures::class,
        ];
    }
}
