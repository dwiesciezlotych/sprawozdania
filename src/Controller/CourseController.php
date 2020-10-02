<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Courses;
use App\Entity\Categories;
use App\Entity\Sections;
use App\Form\CourseAddFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CourseController extends AbstractController
{
    private $resultOnListPageCount = 10; // liczba wynikow na stronie
    
    /**
     * @Route("/course/list", name="app_course_list")
     */
    public function list(Request $request, PaginatorInterface $paginator)
    {
        $categoriesRepository = $this->getDoctrine()->getRepository(Categories::class); 
        $coursesRepository = $this->getDoctrine()->getRepository(Courses::class); 
        // 1 sposob
//        $target = array_merge($coursesRepository->findBy(['category' => null]), $categoriesRepository->findAll());

        // 2 sposob
        $newCategory = (new Categories())->setName("Inne")->setId(-1)->setCourses(new ArrayCollection($coursesRepository->findBy(['category' => null])));
        $target = array_merge([$newCategory], $categoriesRepository->findAll());
        
        return $this->render('course/list.html.twig', [
            'pagination' => $paginator->paginate(
                    
             $target, $request->query->getInt('page', 1), $this->resultOnListPageCount),
            // (lista, numer strony, liczba wynikow na stronie)
        ]);
    }
    
    /**
     * @Route("/course/add", name="app_course_add")
     */
    public function add(Request $request): Response
    {
        $course = new Courses();
        $form = $this->createForm(CourseAddFormType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($course);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_course_list');
        }

        return $this->render('course/add.html.twig', [
            'courseAddForm' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/course/show", name="app_course_show")
     */
    public function show(Request $request, PaginatorInterface $paginator): Response
    {
        $course = $this->getDoctrine()->getManager()->find(Courses::class,$request->query->getInt('cid', -1));
        if($course == null) return $this->redirectToRoute('app_course_list');

        //$target = $this->getDoctrine()->getRepository(Sections::class)->findBy(["course" => $course]);
        $target = $this->getDoctrine()->getRepository(Sections::class)->findAllByCourse($course->getId());
        dd($target);
//        WITH RECURSIVE sub_tree AS (
//  SELECT *
//  FROM sections
//  WHERE previous_section_id is null
//    	and course_id = 2
//
//  UNION ALL
//
//  SELECT sec.*
//  FROM sections sec, sub_tree st
//  WHERE sec.previous_section_id = st.id
//)
//SELECT id, name, course_id, previous_section_id FROM sub_tree
        
        return $this->render('course/show.html.twig', [
            'course' => $course,
            'pagination' => $paginator->paginate(
             $target, $request->query->getInt('page', 1), $this->resultOnListPageCount),
            // (lista, numer strony, liczba wynikow na stronie)
        ]);
    }
    
    /**
     * @Route("/section/add", name="app_section_add")
     */
    public function sectionAdd(Request $request): Response
    {
        $course = $this->getDoctrine()->getManager()->find(Courses::class,$request->query->getInt('cid', -1));
        if($course == null) return $this->redirectToRoute('app_course_list');
        
        $section = new Sections();
        $form = $this->createFormBuilder($section)
            ->add('name', TextType::class)
            ->getForm();
        
        $section->setCourse($course);
        $section->setPreviousSection(null);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($section);
            $entityManager->flush();

            return $this->redirectToRoute('app_course_show',['cid' => $course->getId()]);
        }

        return $this->render('course/sectionAdd.html.twig', [
            'sectionAddForm' => $form->createView(),
        ]);
    }
}
