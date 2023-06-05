<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $userRepository): JsonResponse
    {
        return $this->json([
            'data' => $userRepository->findAll(),
        ]);
    }

    #[Route('/user/{user}', name: 'user_single' , methods:['GET'])]
    public function single(int $user, UserRepository $userRepository): JsonResponse
    {
        $user=  $userRepository->find($user);

        if(!$user) throw $this->createNotFoundException();
        return $this->json([
            'data' => $user,
        ]);
    }

    #[Route('/user', name: 'user_create' , methods:['POST'])]
    public function create(Request $request , UserRepository $userRepository): JsonResponse
    {
        $data = $request->request->all();
        $user = new User();
        $user->setName($data['title']);
        $user->setLastname($data['description']);
        $user->setEmail($data['description']);
        $user->setPassword($data['description']);

        $userRepository->save($user, true);

        return $this->json([
            'message' => 'User create succesfully!',
            'data' => $user
            
        ],201);
    }

    #[Route('/user/{user}', name: 'user_update' , methods:['PUT','PATCH'])]
    public function update(int $user, Request $request , ManagerRegistry $doctrine , UserRepository $userRepository): JsonResponse
    {
    
        $user = $userRepository->find($user);
        if(!$user) throw $this->createNotFoundException();

        $data = $request->request->all();
        $user->setName($data['title']);
        $user->setLastname($data['description']);
        $user->setEmail($data['description']);
        $user->setPassword($data['description']);

        $doctrine->getManager()->flush();

        return $this->json([
            'message' => 'User update succesfully!',
            'data' => $user
            
        ],201);
    }

    #[Route('/user/{user}', name: 'user_delete' , methods:['DELETE'])]
    public function delete(int $user, Request $request ,  UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->find($user);
        $userRepository->remove($user,true);

        return $this->json([
             'data'=>$user
            ]);
    }
}
