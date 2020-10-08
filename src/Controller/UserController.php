<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Users;
use App\Form\RegistrationFormType;
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
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setSalt(md5(time()));
            $user->setStatus($this->getDoctrine()->getManager()->find(\App\Entity\Statuses::class,3)); //change password request
            //$user->setRole($this->getDoctrine()->getManager()->find(\App\Entity\Roles::class, 1));
            $user->setPasswordHash(
                $passwordEncoder->encodePassword(
                    $user,
                    'admin'//$form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            //$entityManager->persist($user);
            //$entityManager->flush();
            // do anything else you need here, like send an email

            //return $this->redirectToRoute('app_user_add');
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
        
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_user_list');
        }

        return $this->render('user/add.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
