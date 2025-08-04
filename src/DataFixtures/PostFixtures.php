<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 2; $i++) {
            $post = new Post();
            $post->setMessage("Ceci est le contenu du post" . $i);
            $post->setUser($this->getReference("user" .$i, User::class));
            // $post->setImageName("default-post.jpg");
            $post->setCreatedAt(new \DateTimeImmutable('now'));
            $manager->persist($post);
            $this->addReference('post'.$i,$post);

        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }
}
