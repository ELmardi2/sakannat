<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends ApplicationType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title', TextType::class, $this->getConfig("Title", "Type your title here")
                )
            ->add(
                    'slug', TextType::class, $this->getConfig(
                    "Address web", "It will be taken automatically", [
                        "required" => false
                    ])
                    )
            ->add(
                'introduction', TextType::class, $this->getConfig(
                    "Introduction", "Type the best introduction to your house")
                    )
            ->add(
                'description', TextareaType::class,$this->getConfig("
                Description", "Type the best description to your house")
                )
            ->add(
                'coverImage', UrlType::class, $this->getConfig(
                    "Cover Image", "Put best cover image to your annonce")
                    )
            ->add(
                'rooms', IntegerType::class, $this->getConfig(
                    "Rooms's number", "Type how many rooms you have here")
                    )
            ->add(
                'price', MoneyType::class, $this->getConfig(
                    "Price", "Type how much you wish as price")
                )
                ->add(
                    'images', CollectionType::class, 
                    [
                        'entry_type' => ImageType::class,
                        'allow_add'  => true,
                        'allow_delete' => true
                    ]
                )
                ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
