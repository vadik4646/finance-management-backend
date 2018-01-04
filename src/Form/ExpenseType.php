<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Currency;
use App\Entity\Expense;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExpenseType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('value')
      ->add('currency', EntityType::class, ['class' => Currency::class])
      ->add('category', EntityType::class, ['class' => Category::class])
      ->add('tags', EntityType::class, ['class' => Tag::class])
      ->add('spentAt');
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(
      [
        'data_class' => Expense::class,
      ]
    );
  }
}
