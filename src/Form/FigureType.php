<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Figure;
use App\Entity\FigureType as EntityFigureType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FigureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('type', EntityType::class, [
                'class' => EntityFigureType::class,
            ])
            ->add('createdAt','hidden_datetime')
            ->add('updatedAt','hidden_datetime')
            ->add('user', EntityType::class, [
                'class' => User::class,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}