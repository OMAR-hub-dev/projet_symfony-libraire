<?php

namespace App\Form;

use App\Entity\User;
use App\Form\AvatarType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('avatar',AvatarType::class)
            ->add('firstname')
            ->add('email',EmailType::class)
            ->add('pseudo')
            ->add('plainPassword',PasswordType::class, ["label"=>"change le mot d passe","required"=>false])
            ->add('confirmPassword',PasswordType::class, ["label"=>"Confirme le mot d passe","required"=>false])
            ->add('country',CountryType::class)
            ->remove('roles')
            ->remove('password')       
            ->remove('isVerified')
           // ->add('Modifier',SubmitType::class,['attr'=>["class"=>"btn-primary mt-3"]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
