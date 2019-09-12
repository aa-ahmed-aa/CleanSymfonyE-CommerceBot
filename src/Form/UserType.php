<?php

namespace App\Form;

use App\Component\User\Model\User;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username', TextType::class, [ 'attr' => [
                    'class' => 'form-control'
            ]])
            ->add('email', EmailType::class, [ 'attr' => [
                'class' => 'form-control'
            ]])
            ->add('password', RepeatedType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'type' => PasswordType::class
            ])
            ->add('register', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-lg btn-primary'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }

}
