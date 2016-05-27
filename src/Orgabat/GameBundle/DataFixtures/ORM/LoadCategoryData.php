<?php

namespace Orgabat\GameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Orgabat\GameBundle\Entity\Category;

class LoadCategoryData implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $data = [
            'Approvisionnement',
            'Déplacements',
            'Circulation',
            'Manutention',
            'Protection',
            'Organisation',
            "Gestion de l'espace de travail",
            'Communication'
        ];

        foreach ($data as $value) {
            $category = new Category;
            $category->setName($value);
            $manager->persist($category);
        }

        // On déclenche l'enregistrement de tous les tags
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}