<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Tag;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends BaseFixture implements DependentFixtureInterface
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

        $this->createMany(30, Article::class, function ($i) use ($manager) {
            $article = new Article();

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
            $tags = $this->getRandomReferences(Tag::class, $this->faker->numberBetween(0, 5));
            foreach ($tags as $tag) {
                $article->addTag($tag);
            }

            if ($this->faker->boolean(70)) {
                $article->setPublishedAt($this->faker->dateTimeBetween('-50 days', '-1 days'));
            }

            return $article;
        });

        $manager->flush();
    }

    private function genName()
    {
        return $this->faker->firstName . ' ' . $this->faker->lastName;
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [TagFixture::class];
    }
}
