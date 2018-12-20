<?php
/**
 * Created by Pavel Burylichau
 * Company: EPAM Systems
 * User: pavel_burylichau@epam.com
 * Date: 10/29/18
 * Time: 2:11 AM
 */


namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

abstract class BaseFixture extends Fixture
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var Generator
     */
    protected $faker;

    /**
     * @var array
     */
    private $referenceIndex = [];

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->faker = Factory::create();
        $this->loadData($manager);
    }

    public function createMany(string $className, int $count, callable $factory)
    {
        for ($i = 0; $i < $count; $i++) {
            $item = new $className();
            $factory($item, $i);

            $this->manager->persist($item);
            // store for usage later as App\Entity\ClassName_#COUNT#
            $this->addReference($className . '_' . $i, $item);
        }
    }

    /**
     * Returns a name or reference to an Object Element created within the loading fixtures.
     *
     * @param string $className
     * @return object
     * @throws \Exception
     */
    protected function getRandomReference(string $className) {
        if (!isset($this->referenceIndex[$className])) {
            $this->referenceIndex[$className] = [];

            foreach ($this->referenceRepository->getReferences() as $key => $ref) {
                if (strpos($key, $className . '_') === 0) {
                    $this->referenceIndex[$className][] = $key;
                }
            }
        }

        if (empty($this->referenceIndex[$className])) {
            throw new \Exception(sprintf('Can\'t find and references for class %s', $className));
        }

        $randomRefKey = $this->faker->randomElement($this->referenceIndex[$className]);

        return $this->getReference($randomRefKey);
    }

    abstract protected function loadData(ObjectManager $manager);
}