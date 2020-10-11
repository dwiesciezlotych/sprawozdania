<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Users;
use App\Entity\Statuses;
use App\Form\RegistrationFormType;
use App\Form\UserEditFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $resultOnListPageCount = 20; // liczba wynikow na stronie
    
    /**
     * @Route("/user/list", name="app_user_list")
     */
    public function list(Request $request, PaginatorInterface $paginator)
    {
        $repository = $this->getDoctrine()->getRepository(Users::class); 
        return $this->render('user/list.html.twig', [
            'pagination' => $paginator->paginate(
             $repository->findAll(),$request->query->getInt('page', 1), $this->resultOnListPageCount),
            // (lista, numer strony, liczba wynikow na stronie)
        ]);
    }
    
    /**
     * @Route("/user/add", name="app_user_add")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new Users();
        $user->setPasswordHash(substr(md5(time()),16));
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setSalt(substr(md5(time()),12));
            //$user->setStatus($this->getDoctrine()->getManager()->find(Statuses::class,Statuses::STATUS_CHANGE_PASSWORD_REQUEST)); //change password request
            $user->setStatus($this->getDoctrine()->getRepository(Statuses::class)->findOneBy(['name' => Statuses::STATUS_CHANGE_PASSWORD_REQUEST]));
            $user->setPasswordHash(
                $passwordEncoder->encodePassword(
                    $user,
                    $user->getPasswordHash()//'admin'//$form->get('plainPassword')->getData()
                )
            );
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_user_add');
        }

        return $this->render('user/add.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/user/edit", name="app_user_edit")
     */
    public function edit(Request $request)
    {
        $user = $this->getDoctrine()->getManager()->find(Users::class,$request->query->getInt('uid', -1));
        if($user == null) return $this->redirectToRoute('app_user_list');
        
        $form = $this->createForm(UserEditFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_user_list');
        }

        return $this->render('user/edit.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/user/changepassword", name="app_user_change_password")
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getDoctrine()->getManager()->find(Users::class,$request->query->getInt('uid', -1));
        if($user == null) return $this->redirectToRoute('app_login');
        
        $form = $this->createFormBuilder($user)
                ->add('password_hash', \Symfony\Component\Form\Extension\Core\Type\RepeatedType::class, [
                    'type' => \Symfony\Component\Form\Extension\Core\Type\PasswordType::class,
                    'invalid_message' => 'Podane dane są niezgodne.',
                    'required' => true,
                    'first_options'  => ['label' => 'Hasło'],
                    'second_options' => ['label' => 'Powtórz hasło'],
                    'data' => '',
                ])
                ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPasswordHash(
                $passwordEncoder->encodePassword(
                    $user,
                    $user->getPasswordHash()
                )
            );
            if($user->getStatus()->getName() == Statuses::STATUS_CHANGE_PASSWORD_REQUEST) {
                $user->setStatus($this->getDoctrine()->getRepository(Statuses::class)->findOneBy(['name' => Statuses::STATUS_ACTIVE]));
            }
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_user_edit', ['uid' => $user->getId()]);
        }

        return $this->render('user/change_password.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
