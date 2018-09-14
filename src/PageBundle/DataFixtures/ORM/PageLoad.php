<?php

namespace PageBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use PageBundle\Entity\Page;
use TermBundle\DataFixtures\ORM\TermLoad;
use TermBundle\Entity\Term;

class PageLoad extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $termRepo = $manager->getRepository(Term::class);
        for ($i = 1; $i <= 3; $i++) {
            $page = new Page();
            $page->setTitle('Page ' . $i);
            $page->setBody('Body Page ' . $i);
            $page->setCreated(new \DateTime());
            $term = $termRepo->findOneBy(array('name' => 'Term ' . $i));
            if ($term) {
                $page->setCategory($term);
            }
            $manager->persist($page);
        }

        $manager->flush();
    }

    public function getDependencies() {
        return [
            TermLoad::class
        ];
    }
}