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

    public function createMany(int $count, string $groupName, callable $factory)
    {
        for ($i = 0; $i < $count; $i++) {
            $entity = $factory($i);

            if (null === $entity) {
                throw new \LogicException('Did you forget to return the entity object from your callback to BaseFixture::createMany()?');
            }

            $this->manager->persist($entity);
            // store for usage later as App\Entity\ClassName_#COUNT#
            $this->addReference(sprintf('%s_%d', $groupName, $i), $entity);
        }
    }

    /**
     * Returns a name or reference to an Object Element created within the loading fixtures.
     *
     * @param string $referenceName
     * @return object
     * @throws \Exception
     */
    protected function getRandomReference(string $referenceName)
    {
        if (!isset($this->referenceIndex[$referenceName])) {
            $this->referenceIndex[$referenceName] = [];

            foreach ($this->referenceRepository->getReferences() as $key => $ref) {
                if (strpos($key, $referenceName . '_') === 0) {
                    $this->referenceIndex[$referenceName][] = $key;
                }
            }
        }

        if (empty($this->referenceIndex[$referenceName])) {
            throw new \Exception(sprintf('Can\'t find and references for class %s', $referenceName));
        }

        $randomRefKey = $this->faker->randomElement($this->referenceIndex[$referenceName]);

        return $this->getReference($randomRefKey);
    }

    /**
     * @param string $referenceName
     * @param int $count
     * @return array
     * @throws \Exception
     */
    protected function getRandomReferences(string $referenceName, int $count)
    {
        $references = [];

        while (count($references) < $count) {
            $references[] = $this->getRandomReference($referenceName);
        }

        return $references;
    }

    abstract protected function loadData(ObjectManager $manager);
}
