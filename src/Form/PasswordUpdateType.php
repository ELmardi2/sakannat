<?php

namespace App\Form;

use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('oldPassword', PasswordType::class, $this->getConfig("Old Password", "Type Your OldPassword")
        )
        ->add('newPassword', PasswordType::class, $this->getConfig("Choice New Password", "Choice your New password")
            )
        ->add('confirmNewPassword', PasswordType::class, $this->getConfig("Confirmation New Password", "Confirm your password")
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
