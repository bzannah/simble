<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <=10; $i++) {
            $blog = new BlogPost();
            $blog->setTitle("In the Dark " . $i)
                 ->setPublished(new \DateTime("2018-12-31 23:59:59"))
                 ->setContent("Boring through the hall number " . $i)
                 ->setAuthor("John Cast " . $i)
                 ->setSlug("in-the-dark-".$i);
            $manager->persist($blog);
        }

        $manager->flush();
    }
}
