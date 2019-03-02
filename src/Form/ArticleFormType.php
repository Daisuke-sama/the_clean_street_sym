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
use function Clue\StreamFilter\fun;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    const SPECIFIC_LOCATION_FIELD_NAME = 'specificLocationName';
    const PRIVATE_SPACE_VARNAME = 'private_space';

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
        /** @var Article|null $article */
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
                    'Private Space' => self::PRIVATE_SPACE_VARNAME,
                ],
            ]);

        $location = $article ? $article->getLocation() : null;
        if ($location) {
            $builder
                ->add(self::SPECIFIC_LOCATION_FIELD_NAME, ChoiceType::class, [
                    'placeholder' => 'Where exactly?',
                    'choices' => $this->getLocationNameChoices($location),
                    'required' => false,
                ]);
        }

        if ($options['include_published_at']) {
            $builder
                ->add('publishedAt', null, [
                    'widget' => 'single_text',
                ]);
        }

        $builder->get('location')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                // The $form variable is the Form object that represents just the location field.
                $form = $event->getForm();
                $this->setupSpecificLocationNameField(
                    $form->getParent(),
                    $form->getData()        // A value of the $form, which is only 'location' field in this case because of ->get('location')
                );
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var Article|null $data */
                $data = $event->getData();
                if (!$data) {
                    return;
                }
                $this->setupSpecificLocationNameField(
                    $event->getForm(),          // now we are working with the top level form, i.e. entire form
                    $data->getLocation()        // so, we need to ask for the specific field against its data
                );
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'include_published_at' => false,
        ]);
    }

    private function getLocationNameChoices(string $location)
    {
        $cities = [
            'NY',
            'LA',
            'PD',
            'CH',
            'MB',
            'BS',
            'TC',
            'DC',
        ];

        $closet = [
            'Polaris',
            'Sirius',
            'Alpha A',
            'Alpha B',
            'Betelgeuse',
            'Rigel',
            'Other'
        ];

        $locationNameChoices = [
            'urban_system' => array_combine($cities, $cities),
            'closet' => array_combine($closet, $closet),
            self::PRIVATE_SPACE_VARNAME => null,
        ];

        return $locationNameChoices[$location] ?? null;
    }

    private function setupSpecificLocationNameField(FormInterface $form, ?string $location)
    {
        $choices = $this->getLocationNameChoices($location);

        if (null === $choices) {
            $form->remove(self::SPECIFIC_LOCATION_FIELD_NAME);

            return;
        }

        $form->add(self::SPECIFIC_LOCATION_FIELD_NAME, ChoiceType::class, [
            'placeholder' => 'Where exactly?',
            'choices' => $choices,
            'required' => false,
        ]);
    }
}
