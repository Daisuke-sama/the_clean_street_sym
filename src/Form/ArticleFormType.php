<?php
/**
 * Created by PhpStorm.
 * User: Pavel_Burylichau
 * Date: 2/28/2019
 * Time: 5:47 PM
 */

namespace App\Form;


use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'help' => 'Make a good title.',
            ])
            ->add('content')
            ->add('publishedAt', null, [
                'widget' => 'single_text',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
