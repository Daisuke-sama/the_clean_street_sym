<?php
/**
 * Created by PhpStorm.
 * User: Pavel_Burylichau
 * Date: 2/28/2019
 * Time: 5:47 PM
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ArticleFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content');
    }
}
