<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'main_users', function ($i) use ($manager) {
            $user = new User();
            $user->setEmail(sprintf('gouser%d@this.com', $i));
            $user->setFirstName($this->faker->firstName);
            $user->agreeToTerms();
            $password = $this->passwordEncoder->encodePassword($user, 'noadmin');
            $user->setPassword($password);

            if ($this->faker->boolean) {
                $user->setTwitterUsername($this->faker->userName);
            }

            $token1 = new ApiToken($user);
            $manager->persist($token1);
            $token2 = new ApiToken($user);
            $manager->persist($token2);

            return $user;
        });

        $this->createMany(3, 'admin_users', function ($i) {
            $user = new User();
            $user->setEmail(sprintf('admin%d@this.com', $i));
            $user->setFirstName($this->faker->firstName);
            $user->agreeToTerms();
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'admin'));

            $user->setRoles(['ROLE_ADMIN']);

            return $user;
        });

        $manager->flush();
    }
}
