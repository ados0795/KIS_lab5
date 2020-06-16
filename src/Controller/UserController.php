<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    public function getUsers () {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();
        if (!$users){
            return new Response("Таблица пуста!");
        }
        $arrayCollection = array();

        foreach($users as $user) {
            $arrayCollection[] = array(
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'phone' => $user->getPhone()
            );
        }

        return new JsonResponse($arrayCollection);
    }

    public function getUser1 ($id) {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);
        if (!$user){
            return new Response('User not found');
        }
        $userJSON = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone()
        ];
        return new JsonResponse($userJSON);
    }

    public function createUser (Request $request): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $user = new User();
        $user->setName($request->request->get('name'));
        $user->setEmail($request->request->get('email'));
        $user->setPhone($request->request->get('phone'));
        $entityManager->persist($user);
        $entityManager->flush();
        return new Response('Пользователь был успешно создан, идентификатор: '.$user->getId());
    }

    public function patchUser ($id, Request $request): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);
        if (!$user) {
            return new Response('Пользователь не существует');
        } else {
            $user->setName($request->request->get('name'));
            $user->setEmail($request->request->get('email'));
            $user->setPhone($request->request->get('phone'));
            $entityManager->persist($user);
            $entityManager->flush();
            return new Response('Пользователь был успешно изменен, идентификатор: ' . $user->getId());
        }
    }

    public function deleteUser ($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);
        if (!$user) return new Response('Пользователь не найден');
        $entityManager->remove($user);
        $entityManager->flush();
        return new Response('Пользователь с идентификатором '.$id.' был успешно удален');
    }
}
