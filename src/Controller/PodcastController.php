<?php

namespace App\Controller;

use App\Entity\Podcast;
use App\Repository\PodcastRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class PodcastController extends AbstractController
{
    #[Route('/podcast', name: 'podcast_list' , methods:['GET'])]
    public function index(PodcastRepository $podcastRepository): JsonResponse
    {
        return $this->json([
            'data' => $podcastRepository->findAll(),
        ]);
    }

    #[Route('/podcast/{podcast}', name: 'podcast_single' , methods:['GET'])]
    public function single(int $podcast, PodcastRepository $podcastRepository): JsonResponse
    {
        $podcast=  $podcastRepository->find($podcast);

        if(!$podcast) throw $this->createNotFoundException();
        return $this->json([
            'data' => $podcast,
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
    #[Route('/podcast/{podcast}', name: 'podcast_update' , methods:['PUT','PATCH'])]
    public function update(int $podcast, Request $request , ManagerRegistry $doctrine , PodcastRepository $podcastRepository): JsonResponse
    {
    
        $podcast = $podcastRepository->find($podcast);
        if(!$podcast) throw $this->createNotFoundException();

        $data = $request->request->all();
        $podcast->setTitle($data['title']);
        $podcast->setUploadDate (new \DateTimeImmutable ('Europe/Madrid'));
        $podcast->setDescription($data['description']);

        $doctrine->getManager()->flush();

        return $this->json([
            'message' => 'Podcast update succesfully!',
            'data' => $podcast
            
        ],201);
    }

    #[Route('/podcast/{podcast}', name: 'podcast_update' , methods:['DELETE'])]
    public function delete(int $podcast, Request $request ,  PodcastRepository $podcastRepository): JsonResponse
    {
        $podcast = $podcastRepository->find($podcast);
        $podcastRepository->remove($podcast,true);

        return $this->json([
             'data'=>$podcast
            ]);
    }
}
