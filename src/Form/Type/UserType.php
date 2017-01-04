<?php

namespace Wanjee\Shuwee\AdminBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Wanjee\Shuwee\AdminBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @var AuthorizationChecker
     */
    private $authorizationChecker;

    /**
     * ProgressionExtension constructor.
     * @param AuthorizationChecker $authorizationChecker
     */
    public function __construct(AuthorizationChecker $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            array($this, 'onPreSetData')
        );

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
                    ],
                    'help' => 'Leave empty to keep previous password.'
                ]
            );
    }

    /**
     * Set appropriate field elements in form
     * @param FormEvent $event
     */
    function onPreSetData(FormEvent $event)
    {
        $form = $event->getForm();
        if ($this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) {
            $roles = [
                'User' => 'ROLE_USER',
                'Admin' => 'ROLE_ADMIN',
                'Super admin' => 'ROLE_SUPER_ADMIN',
            ];
            $form
                ->add(
                    'roles',
                    ChoiceType::class,
                    [
                        'label' => 'Roles',
                        'required' => true,
                        'expanded' => true,
                        'multiple' => true,
                        'choices' => $roles,
                    ]
                );
        }
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
