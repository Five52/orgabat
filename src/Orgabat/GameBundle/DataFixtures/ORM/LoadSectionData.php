<?php

namespace Orgabat\GameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Orgabat\GameBundle\Entity\Section;

class LoadSectionData implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $data = [
            'CAP 1',
            'CAP 2',
            'BEP 1',
            'BEP 2',
            'CAP 3',
            'BEP 3'
        ];

        foreach ($data as $value) {
            $section = new Section();
            $section->setName($value);
            $manager->persist($section);
        }

        // On déclenche l'enregistrement de tous les tags
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}