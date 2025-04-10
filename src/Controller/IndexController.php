<?php

namespace App\Controller;

use App\Repository\SectionsRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(SectionsRepository $sectionsRepository,UserRepository $userRepository): Response
    {
        $sections = $sectionsRepository->findAll();
        $sectionCount = count($sections);
        $users = $userRepository->findAll();
        $userCount = count($users);
        $foot = $sectionsRepository->find('4');
        $eng = $sectionsRepository->find('6');
        $thek = $sectionsRepository->find('5');
        $dance = $sectionsRepository->find('7');

        return $this->render('index/index.html.twig', [
            'sectionCount' => $sectionCount,
            'userCount' => $userCount,
            'foot' => $foot,
            'eng' => $eng,
            'thek' => $thek,
            'dance' => $dance
        ]);
    }

    #[Route('/api/about', name: 'app_about')]
    public function about()
    {
        return new JsonResponse(
            [
                ['name' => "Akai", "id" => 1],
                ['name' => "Eldar", "id" => 2]
            ]
        );

    }

}
