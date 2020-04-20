<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfig('Prénom', 'Votre prénom'))
            ->add('lastName', TextType::class, $this->getConfig('Nom', 'Votre nom de famille'))
            ->add('email', EmailType::class, $this->getConfig('Email', 'Votre adresse email'))
            ->add('pictureFile', FileType::class, $this->getConfig('Avatar', 'Votre avatar', ['required' => false]))
            ->add('hash', PasswordType::class, $this->getConfig('Mot de passe', 'Mot de passe'))
            ->add('passwordConfirm', PasswordType::class, $this->getConfig('Confirmation mot de passe', 'confirmez le mot de passe'))
            ->add('introduction', TextType::class, $this->getConfig('Introduction'))
            ->add('description', TextareaType::class, $this->getConfig('Description', 'Présentez-vous'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
