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

class UserEditSelfFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, 
                    ['label' => 'Imię',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Wprowadź imię',
                        ]),
                        new Length([
                            'max' => 50,
                            'maxMessage' => 'Imię może zawierać maksymalnie {{ limit }} znaków',
                        ]),
                    ],
                    'disabled' => true,])
            ->add('surname', TextType::class, 
                    ['label' => 'Nazwisko',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Wprowadź nazwisko',
                        ]),
                        new Length([
                            'max' => 50,
                            'maxMessage' => 'Nazwisko może zawierać maksymalnie {{ limit }} znaków',
                        ]),
                    ],
                    'disabled' => true,])
            ->add('email', EmailType::class, 
                    ['label' => 'Email',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Wprowadź email',
                        ]),
                        new Length([
                            'max' => 50,
                            'maxMessage' => 'Email może zawierać maksymalnie {{ limit }} znaków',
                        ]),
                    ],])
            ->add('telephone', TelType::class, 
                    ['label' => 'Telefon',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Wprowadź telefon',
                        ]),
                        new Length([
                            'max' => 15,
                            'maxMessage' => 'Telefon może zawierać maksymalnie {{ limit }} znaków',
                        ]),
                    ],])
            ->add('birth_date', BirthdayType::class, 
                    ['label' => 'Data urodzenia',
                    'disabled' => true,])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
