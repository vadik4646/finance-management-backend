<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Currency;
use App\Entity\Income;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class IncomeType extends AbstractType
{
  private $entityManager;
  private $tokenStorage;

  public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
  {
    $this->entityManager = $entityManager;
    $this->tokenStorage = $tokenStorage;
  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('value')
      ->add('currency', EntityType::class, ['class' => Currency::class])
      ->add('user', EntityType::class, ['class' => User::class])
      ->add('category', EntityType::class, ['class' => Category::class])
      ->add('spentAt', DateTimeType::class, ['widget' => 'single_text', 'format' => 'dd-MM-yyyy HH:mm'])
      ->add('tags', EntityType::class, [
        'class' => Tag::class,
        'allow_extra_fields' => true,
        'multiple' => true
      ]);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(
      [
        'data_class'         => Income::class,
        'allow_extra_fields' => true,
        'allow_add'          => true
      ]
    );
  }
}
