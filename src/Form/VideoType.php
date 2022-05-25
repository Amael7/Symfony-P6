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
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          ->add('url', UrlType::class, [
            'label' => false,
            'attr' => [
                'placeholder' => 'Insérer l\'URL de la vidéo',
            ],
          ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
          'data_class' => Video::class,
        ]);
    }
}