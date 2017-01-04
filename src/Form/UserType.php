<?php

namespace Wanjee\Shuwee\AdminBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Wanjee\Shuwee\AdminBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username',
                TextType::class,
                [
                    'label' => 'Username',
                    'required' => true,
                    'attr' => [
                        'autocomplete' => 'off',
                    ]
                ]
            )
            ->add(
                'plainPassword',
                PasswordType::class,
                [
                    'required' => false,
                    'mapped' => true,
                    'attr' => [
                        'autocomplete' => 'off',
                    ]
                ]
            )
            ->add(
                'roles',
                ChoiceType::class,
                [
                    'choices' => [
                        'Admin' => 'ROLE_ADMIN',
                        'Super admin' => 'ROLE_SUPER_ADMIN',
                    ],
                    'multiple' => true,
                    'expanded' => true,
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => User::class,
            )
        );
    }
}
