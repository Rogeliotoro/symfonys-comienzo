<?php

namespace App\Controller;

use App\Entity\Podcast;
use App\Repository\PodcastRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PodcastController extends AbstractController
{
    #[Route('/podcast', name: 'podcast_list' , methods:['GET'])]
    public function index(PodcastRepository $podcastRepository): JsonResponse
    {
        return $this->json([
            'data' => $podcastRepository->findAll(),
        ]);
    }
    #[Route('/podcast', name: 'podcast_create' , methods:['POST'])]
    public function create(Request $request , PodcastRepository $podcastRepository): JsonResponse
    {
        $data = $request->request->all();
        $podcast = new Podcast();
        $podcast->setTitle($data['title']);
     
        $podcast->setUploadDate (new \DateTimeImmutable ('Europe/Madrid'));
        $podcast->setDescription($data['description']);

        $podcastRepository->save($podcast, true);

        return $this->json([
            'message' => 'Podcast create succesfully!',
            'data' => $podcast
            
        ],201);
    }
}
