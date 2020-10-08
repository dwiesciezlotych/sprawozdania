<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Task;
use App\Entity\SectionElements;
use App\Entity\Categories;
use App\Entity\Sections;
use App\Form\TaskAddFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TaskController extends AbstractController
{
    /**
     * @Route("/task/add", name="app_task_add")
     */
    public function add(Request $request): Response
    {
        $section = $this->getDoctrine()->getManager()->find(Sections::class,$request->query->getInt('sid', -1));
        if($section == null) return $this->redirectToRoute('app_course_list');
        
        $task = new Task();
        $form = $this->createForm(TaskAddFormType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $section_element = new SectionElements();
            $section_element->setName($form->get('name')->getData());
            $section_element->setDescription($form->get('description')->getData());
            $section_element->setSection($section);
            $section_element->setPreviousElement(null); // do zmiany
            
            $task->setCreateDate(new DateTime);
            $task->setElement($section_element);
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($section_element);
            $entityManager->persist($task);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_course_list');
        }

        return $this->render('task/add.html.twig', [
            'taskAddForm' => $form->createView(),
        ]);
    }
    
}
