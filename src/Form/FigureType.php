<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Image;
use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use App\Entity\FigureType as EntityFigureType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FigureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $now = new \DateTime();
        
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('type', EntityType::class, [
                'class' => EntityFigureType::class,
            ])
            ->add('createdAt', HiddenType::class, [
                'empty_data' => $now->format('d/m/Y H:i:s'),
            ])
            ->add('updatedAt', HiddenType::class, [
            'data' => $now->format('d/m/Y H:i:s'),
            ])
            ->add('images', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => true,
            ])
            ->add('save', SubmitType::class, array('label' => 'Valider'));

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'data_class' => User::class,
        ]);
    }
}