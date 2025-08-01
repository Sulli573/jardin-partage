<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 2; $i++) {
            $comment = new Comment();
            $comment->setMessage("Ceci est un commentaire");
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setPost($this->getReference("post" . $i, Post::class));
            $comment->setUser($this->getReference("user1", User::class));
            $manager->persist($comment);

            $comment = new Comment();
            $comment->setMessage("Ceci est un commentaire");
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setPost($this->getReference("post" . $i, Post::class));
            $comment->setUser($this->getReference("user2", User::class));
            $manager->persist($comment);

        }

        $manager->flush();
    }

     public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            PostFixtures::class,
            
        ];
    }
}
