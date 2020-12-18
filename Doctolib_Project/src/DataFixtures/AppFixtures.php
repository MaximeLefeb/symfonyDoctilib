<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture {
    public function load(ObjectManager $manager) {
        for($i=0;$i<=5;$i++){
            $patient = (new Patient())
                ->setNom("David $i")
                ->setPrenom("Dupont $i")
                ->setAge(20+$i)
                ->setEmail("sam$i@gmail.com")
                ->setPassword("sam$i")
            ;
            $manager->persist($patient);
        }
        $manager->flush();
    }
}
