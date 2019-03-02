<?php
/**
 * Created by PhpStorm.
 * User: Pavel_Burylichau
 * Date: 2/28/2019
 * Time: 5:47 PM
 */

namespace App\Form;


use App\Entity\Article;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $article = $options['data'] ?? null;
        $isEdit = $article && $article->getId();

        $builder
            ->add('title', TextType::class, [
                'help' => 'Make a good title.',
            ])
            ->add('content', null, [
                'rows' => 15,
            ])
            ->add('author', UserSelectTextType::class, [
                'disabled' => $isEdit,
            ])
            ->add('location', ChoiceType::class, [
                'placeholder' => 'Choose a location',
                'required' => false,
                'choices' => [
                    'The Urban System' => 'urban_system',
                    'Near a closet' => 'closet',
                    'Private Space' => 'private_space'
                ],
            ])
            ->add('specificLocationName', ChoiceType::class, [
                'placeholder' => 'Where exactly?',
                'choices' => [
                    'TODO' => 'TODO'
                ],
                'required' => false,
            ])
        ;

        if ($options['include_published_at']) {
            $builder
                ->add('publishedAt', null, [
                    'widget' => 'single_text',
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'include_published_at' => false,
        ]);
    }
}
