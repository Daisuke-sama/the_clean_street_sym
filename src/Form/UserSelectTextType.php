<?php
/**
 * Created by PhpStorm.
 * User: Pavel_Burylichau
 * Date: 3/2/2019
 * Time: 6:21 PM
 */

namespace App\Form;


use App\Form\DataTransformer\EmailToUserTransformer;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSelectTextType extends AbstractType
{
    private $userRepository;

    /**
     * UserSelectTextType constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new EmailToUserTransformer($this->userRepository));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'invalid_message' => 'User doesn\'t exist.',
        ]);
    }

    public function getParent()
    {
        return TextType::class;
    }
}
