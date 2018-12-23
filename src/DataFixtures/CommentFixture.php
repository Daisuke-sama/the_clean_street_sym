<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use function Clue\StreamFilter\fun;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CommentFixture extends BaseFixture implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(100, Comment::class, function($i) {
            $comment = new Comment();
            $comment->setContent(
                $this->faker->sentences(2, true)
            );
            $comment->setAuthorName($this->faker->name);
            $comment->setCreatedAt($this->faker->dateTimeBetween('-1 month', '-1 second'));
            $comment->setIsDeleted($this->faker->boolean(75));

            $comment->setArticle($this->getRandomReference(Article::class));

            return $comment;
        });
//        $this->createMany(Comment::class, 100, function (Comment $comment) {
//            $comment->setContent(
//                $this->faker->sentences(2, true)
//            );
//            $comment->setAuthorName($this->faker->name);
//            $comment->setCreatedAt($this->faker->dateTimeBetween('-1 month', '-1 second'));
//            $comment->setIsDeleted($this->faker->boolean(75));
//
//            $comment->setArticle($this->getRandomReference(Article::class));
//        });

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [ArticleFixtures::class];
    }
}
