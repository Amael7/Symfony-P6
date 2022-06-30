<?php

namespace App\Form;

use DateTime;
use Symfony\Component\Form\AbstractType;
use App\Entity\FigureType as EntityFigureType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FigureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $now = new \DateTime();
        
        $builder
            ->add('name', TextType::class, [
                        'label' => 'Nom de la figure',
                        'attr' => ['placeholder' => "Nom de la figure"], 
                    ]
                )
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['placeholder' => "Description"],
            ])
            ->add('type', EntityType::class, [
                'label' => 'Type de figure',
                'class' => EntityFigureType::class,
            ])
            ->add('createdAt', DateTimeType::class, [
                'data' => $now,
                'widget' => 'single_text',
            ])
            ->add('updatedAt', DateTimeType::class, [
                'data' => $now,
                'widget' => 'single_text', #->format('d/m/Y H:i:s'),
            ])
            ->add('images', FileType::class, [
                'empty_data' => ["default_image.jpeg"],
                'label' => "Fichier de l'image",
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'invalid_message' => "Veuillez choisir une photo au minimum.",
                'attr' => ['placeholder' => "Fichier de l'image"]
            ])
            ->add('videos', TextType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'label' => "Ajouter une vidéo",
                    'placeholder' => "Insérer l'URL de la vidéo"
                ]
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