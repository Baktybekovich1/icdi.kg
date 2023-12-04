<?php

namespace App\Controller;

use App\Entity\Sections;
use App\Form\SectionAddType;
use App\Repository\SectionsRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class SectionController extends AbstractController
{
    #[Route('/section', name: 'app_section')]
    public function index(SectionsRepository $repository, Request $request): Response
    {
        $search = $request->get('search');
        if ($request->get('search') != null ) {

            $query = $repository->createQueryBuilder('s')
                ->where('s.description LIKE :search')
                ->setParameter('search', '%'.$search.'%')
                ->getQuery();

            $sections = $query->getResult();

        }
        else {
            $sections = $repository->findAll();
        }


        return $this->render('section/index.html.twig', [
            'sections' => $sections,
        ]);
    }

    #[Route('/section/add', name: 'app_section_add')]
    public function add(Request $request, SectionsRepository $repository): Response
    {
        $entity = new Sections();
        $form = $this->createForm(SectionAddType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /* @var UploadedFile $uploadedFile */
            $uploadedFile = $form->get('img')->getData();

            if ($uploadedFile) {
                $newFilename = uniqid() . '.' . $uploadedFile->guessExtension();
                $uploadedFile->move('uploads', $newFilename);
                $entity->setImg($newFilename);
            }

            $entity = $form->getData();
            $repository->save($entity);
            return $this->redirectToRoute("app_index");
        }

        return $this->render('section/add.html.twig', ['FormType' => $form->createView()]);
    }

//    #[Route('/section/delete', name: 'app_section_delete')]
//    public function delete(Request $request, SectionsRepository $repository,ManagerRegistry $managerRegistry): Response
//    {
//        $repository->remove($page);
//        $managerRegistry->getManager()->flush();
//    }

    #[Route('/section/more/{id}', name: 'app_section_more')]
    public function more(SectionsRepository $repository,Request $request): Response
    {
        $id = $request->get('id');
        $section = $repository->find($id);
//        dd($section);
        return $this->render('section/more.html.twig', [
            'section' => $section,
        ]);
    }

}
