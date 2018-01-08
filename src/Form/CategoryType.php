<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name')
      ->add('user', EntityType::class, ['class' => User::class]);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(
      [
        'data_class' => Category::class,
        'allow_extra_fields' => true
      ]
    );
  }
}
