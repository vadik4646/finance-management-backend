<?php

namespace App\Form;

use App\Entity\Customization;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomizationType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name')
      ->add('value')
      ->add('user', EntityType::class, ['class' => User::class]);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(
      [
        'data_class'         => Customization::class,
        'allow_extra_fields' => true,
      ]
    );
  }
}
