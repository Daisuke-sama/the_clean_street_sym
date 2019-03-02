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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class UserSelectTextType extends AbstractType
{
    const CSS_CLASS_AUTOCOMPLETE = 'js-user-autocomplete';
    const JS_DATA_ATTR = 'data-autocomplete-url';

    private $userRepository;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * UserSelectTextType constructor.
     * @param UserRepository $userRepository
     * @param RouterInterface $router
     */
    public function __construct(UserRepository $userRepository, RouterInterface $router)
    {
        $this->userRepository = $userRepository;
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(
            new EmailToUserTransformer(
                $this->userRepository,
                $options['finder_callback']
            )
        );
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $attr = $view->vars['attr'];

        $class = isset($attr['class']) ? $attr['class'].' ' : '';
        $class .= self::CSS_CLASS_AUTOCOMPLETE;

        $attr['class'] = $class;
        $attr[self::JS_DATA_ATTR] = $this->router->generate('admin_utility_users');

        $view->vars['attr'] = $attr;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'invalid_message' => 'User doesn\'t exist.',
            'finder_callback' => function (UserRepository $userRepository, string $email) {
                return $userRepository->findOneBy(['email' => $email]);
            },
        ]);
    }

    public function getParent()
    {
        return TextType::class;
    }
}
