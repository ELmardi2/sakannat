<?php

namespace App\Form;

use App\Entity\Comment;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rating', IntegerType::class, $this->getConfig("Give Your Rating", "Would You Give Your Rating From 1 To 5"
            , [
                'attr' => [
                    'min'=>1,
                    'max'=> 5,
                    'step'=>1
                ]
            ]))
            ->add('content', TextareaType::class, $this->getConfig("Your Opinion Required",
             "Be sur about what you write to give us the possibility to do the neccessary!"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
