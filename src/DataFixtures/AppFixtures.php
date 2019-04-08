<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var Factory
     */
    private $faker;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadBlogPosts($manager);
        $this->loadComments($manager);
    }

    public function loadBlogPosts(ObjectManager $manager)
    {
        for ($i = 1; $i <=100; $i++) {
            /** @var User $user */
            $user = $this->getRandomUser();
            $blog = new BlogPost();
            $blog->setTitle($this->faker->realText(20))
                ->setPublished($this->faker->dateTime)
                ->setContent($this->faker->realText())
                ->setAuthor($user)
                ->setSlug($this->faker->slug);
            $this->addReference('blog_post_'.$i, $blog);
            $manager->persist($blog);
        }

        $manager->flush();
    }

    public function loadComments(ObjectManager $manager)
    {
        for ($i = 1; $i <=100; $i++) {
            /** @var User $user */
            $user = $this->getRandomUser();
            $this->createCommentPerUser($user, $manager);
        }
    }

    public function loadUsers(ObjectManager $manager)
    {
        for ($i = 1; $i <=10; $i++) {
            $user = new User();
            $username = $i == 1 ? 'foo' : $this->faker->userName;
            $email = $i == 1 ? 'admin@example.com' : $this->faker->email;
            $user->setUsername($username)
                ->setEmail($email)
                ->setName($this->faker->name)
                ->setPassword($this->passwordEncoder->encodePassword($user, 'Foobar123#'));
            $this->addReference('user_'.$i, $user);
            $manager->persist($user);
        }
        $manager->flush();
    }

    private function createCommentPerUser(User $user, ObjectManager $manager)
    {
        $limit = random_int(1, 5);
        for ($i = 1; $i <= $limit; $i++) {
            $rand = random_int(1, 100);
            /** @var BlogPost $blogPost */
            $blogPost = $this->getReference('blog_post_'.$rand);
            $comment = new Comment();
            $comment->setContent($this->faker->realText())
                ->setPublished($this->faker->dateTimeThisMonth)
                ->setAuthor($user)
                ->setBlogPost($blogPost);
            $manager->persist($comment);
        }
        $manager->flush();
    }

    /**
     * @return User|object
     */
    private function getRandomUser() : User
    {
        return $this->getReference('user_'.random_int(1, 10));
    }
}
