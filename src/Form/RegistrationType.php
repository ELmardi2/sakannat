<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ImageType;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfig("Firstname", "Type your firstName here")
            )
            ->add('lastName', TextType::class, $this->getConfig("Lastname", "Type your lastName here")
            )
            ->add('email', EmailType::class, $this->getConfig("Email", "Type your email here")
            )
            ->add('picture', UrlType::class, $this->getConfig("Picture", "choice your photo ")
            )
            ->add('hash', PasswordType::class, $this->getConfig("Password", "Choice best password here")
            )
            ->add('confirmPassword', PasswordType::class, $this->getConfig("Confirmation Password", "Confirm your password")
            )
            ->add('introduction', TextType::class, $this->getConfig("Introduction", "Resume yourself")
            )
            ->add('description', TextareaType::class, $this->getConfig("Description", "Talk about yourself here")
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
