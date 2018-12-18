<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoder
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoder $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadBlogPosts($manager);
    }

    public function loadBlogPosts(ObjectManager $manager)
    {
        for ($i = 1; $i <=10; $i++) {
            /** @var User $user */
            $user = $this->getReference('user_admin_'.random_int(1, 10));
            $blog = new BlogPost();
            $blog->setTitle("In the Dark " . $i)
                ->setPublished(new \DateTime("2018-12-31 23:59:59"))
                ->setContent("Boring through the hall number " . $i)
                ->setAuthor($user)
                ->setSlug("in-the-dark-".$i);
            $manager->persist($blog);
        }

        $manager->flush();
    }

    public function loadComments(ObjectManager $manager)
    {

    }

    public function loadUsers(ObjectManager $manager)
    {
        for ($i = 1; $i <=10; $i++) {
            $user = new User();
            $user->setUsername('admin'.$i)
                ->setEmail('admin@example.com')
                ->setName('Foo Bar')
                ->setPassword($this->passwordEncoder->encodePassword($user, 'foo'));
            $this->addReference('user_admin_'.$i, $user);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
