<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Common\Persistence\ObjectManager;

class TagFixture extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, Tag::class, function ($i) {
            $tag = new Tag();
            $tag->setName($this->faker->realText(20));

            return $tag;
        });
//        $this->createMany(Tag::class, 10, function (Tag $tag) {
//            $tag->setName($this->faker->realText(20));
//        });

        $manager->flush();
    }
}
