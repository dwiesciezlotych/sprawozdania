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
//        $target = array_merge([$newCategory], $categoriesRepository->findAll());
        $target = array_merge($categoriesRepository->findBy([],['name' => 'ASC']),[$newCategory]);
        
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
     * @Route("/course/edit", name="app_course_edit")
     */
    public function edit(Request $request): Response
    {
        $course = $this->getDoctrine()->getManager()->find(Courses::class,$request->query->getInt('cid', -1));
        if($course == null) return $this->redirectToRoute('app_course_list');
        
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
        $section->setPreviousSection(
                $this->getDoctrine()->getRepository(Sections::class)->findLastInCourse(
                                $course->getId()));
        
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
    
    /**
     * @Route("/section/move", name="app_section_move")
     */
    public function sectionMove(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $section = $entityManager->find(Sections::class,$request->query->getInt('sid', -1));
        $method = $request->query->get('method', null);
        
        if($section != null){
            switch($method){
                case 'up':
                    $previousSection = $section->getPreviousSection();
                    if(true){//$previousSection != null){
//                        $section->setPreviousSection(null);
//                        $previousSection->setPreviousSection(null);
//                        $entityManager->flush();
//                        $section->setPreviousSection($previousSection->getPreviousSection());
//                        $previousSection->setPreviousSection($section);
//                        $entityManager->persist($section);
//                        $entityManager->persist($previousSection);
//                        $args = [$section->getId() => ['previous_section_id' => $previousSection->getPreviousSection()->getId()],
//                                $previousSection->getId() => ['previous_section_id' => $section->getId()]];
                        
                        
                        
//                        $args = [$section->getId() => $previousSection->getPreviousSection()->getId(),
//                                $previousSection->getId() => $section->getId()];
                        $args = [3 => 1,
                                2 => 3];
                        
                        $nextSection = $this->getDoctrine()->getRepository(Sections::class)->findOneBy(['previousSection' => $section->getId()]);
                        if(true){//$nextSection != null){
//                            $nextSection->setPreviousSection($previousSection);
//                            $entityManager->persist($nextSection);
                            
                            //$args += [$nextSection->getId() => ['previous_section_id' => $previousSection->getId()]];
                            //
                            //
//                            $args += [$nextSection->getId() => $previousSection->getId()];
                            $args += [4 => 2];
                            
                        }
                        $this->getDoctrine()->getRepository(Sections::class)->updateManyRowsInOneColumn('previous_section_id',$args);
//                        $entityManager->flush();
                    }
                    break;
                case 'down':
                    $nextSection = $this->getDoctrine()->getRepository(Sections::class)->findOneBy(['previousSection' => $section->getId()]);
                    if($nextSection != null){
                        $nextSection->setPreviousSection($section->getPreviousSection());
                        $section->setPreviousSection($nextSection);
//                        $entityManager->persist($nextSection);
//                        $entityManager->persist($section);
                        $next2Section = $this->getDoctrine()->getRepository(Sections::class)->findOneBy(['previousSection' => $nextSection->getId()]);
                        if($next2Section != null){
                            $next2Section->setPreviousSection($section);
//                            $entityManager->persist($next2Section);
                        }
                        $entityManager->flush();
                    }
                    
                    break;
            }

        }
        return $this->render('base.html.twig');
        //return $this->redirectToRoute('app_course_show',['cid' => $section->getCourse()->getId()]);
//        return $this->render('course/sectionAdd.html.twig', [
//            'sectionAddForm' => $form->createView(),
//        ]);
    }
}
