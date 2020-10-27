<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Categories;
use App\Form\CategoryAddFormType;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/add", name="app_category_add")
     */
    public function add(Request $request): Response
    {
        $category = new Categories();
        $form = $this->createForm(CategoryAddFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_course_list');
        }

        return $this->render('category/add.html.twig', [
            'categoryAddForm' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/category/edit", name="app_category_edit")
     */
    public function edit(Request $request): Response
    {
        $category = $this->getDoctrine()->getManager()->find(Categories::class,$request->query->getInt('catid', -1));
        if($category == null) return $this->redirectToRoute('app_course_list');
        $form = $this->createForm(CategoryAddFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_course_list');
        }

        return $this->render('category/add.html.twig', [
            'categoryAddForm' => $form->createView(),
        ]);
    }
}
