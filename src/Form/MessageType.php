<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Figure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $now = new \DateTime('now');
        $builder
            ->add('content', TextareaType::class)
            ->add('figure', EntityType::class, [
                'class' => Figure::class,
            ])
            ->add('createdAt', DateTimeType::class, [
                'data' => $now,
                'widget' => 'single_text',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
            ])
            ->add('save', SubmitType::class, array('label' => 'Créer un commentaire'));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'data_class' => User::class,
        ]);
    }
}