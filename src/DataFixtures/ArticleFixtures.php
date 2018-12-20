<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends BaseFixture
{
    private static $articleImages = [
        'asteroid.jpg',
        'mercury.jpeg',
        'lightspeed.png',
    ];
    private static $articleAuthors = [
        'Mike Ferengi',
        'Amy Oort',
    ];

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(Article::class, 30, function (Article $article, int $number) use ($manager) {
            $article
                ->setTitle(
                    $this->faker->words($this->faker->numberBetween(2, 5), true)
                )
                ->setAuthor($this->genName())
                ->setLikeCount(
                    $this->faker->numberBetween(1, 100)
                )
                ->setImageFilename(
                    $this->faker->randomElement(self::$articleImages)
                )
                ->setContent(
                    $this->faker->paragraphs(
                        $this->faker->numberBetween(2, 5),
                        true
                    )
                );
            if ($this->faker->boolean(70)) {
                $article->setPublishedAt($this->faker->dateTimeBetween('-50 days', '-1 days'));
            }
        });

        $manager->flush();
    }

    private function genName()
    {
        return $this->faker->firstName . ' ' . $this->faker->lastName;
    }
}
