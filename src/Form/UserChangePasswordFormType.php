<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password_hash', \Symfony\Component\Form\Extension\Core\Type\RepeatedType::class, [
                    'type' => \Symfony\Component\Form\Extension\Core\Type\PasswordType::class,
                    'invalid_message' => 'Podane dane są niezgodne.',
                    'required' => true,
                    'first_options'  => ['label' => 'Hasło',
                                        'constraints' => [
                                        new NotBlank([
                                            'message' => 'Wprowadź hasło',
                                        ]),
                                        new Length([
                                            'max' => 30,
                                            'maxMessage' => 'Hasło może zawierać maksymalnie {{ limit }} znaków',
                                        ]),
                    ],],
                    'second_options' => ['label' => 'Powtórz hasło',
                                        'constraints' => [
                                        new NotBlank([
                                            'message' => 'Powtórz hasło',
                                        ]),
                                        new Length([
                                            'max' => 30,
                                        ]),
                    ],],
                    'data' => '',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
