<?php


namespace App\Controller;

use App\Entity\Game;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GameController extends AbstractController
{
    public function getGames () {
        $games = $this->getDoctrine()
            ->getRepository(Game::class)
            ->findAll();
        if (!$games){
            return new Response("Таблица пуста!");
        }
        $arrayCollection = array();

        foreach($games as $game) {
            $arrayCollection[] = array(
                'id' => $game->getId(),
                'title' => $game->getTitle(),
                'description' => $game->getDescription(),
                'price' => $game->getPrice(),
                'logo' => $game->getLogo()
            );
        }
        return new JsonResponse($arrayCollection);
    }

    public function getUserGames ($userId) {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($userId);
        if (!$user){
            return new Response('Пользователь не найден');
        }
        $games = $user->getLibrary();
        if (!$games) return new Response('У пользователя нет игр');
        foreach($games as $game) {
            $arrayCollection[] = array(
                'id' => $game->getId(),
                'title' => $game->getTitle(),
                'description' => $game->getDescription(),
                'price' => $game->getPrice(),
                'logo' => $game->getLogo()
            );
        }
        return new JsonResponse($arrayCollection);
    }

    public function getGame ($id) {
        $game = $this->getDoctrine()
            ->getRepository(Game::class)
            ->find($id);
        if (!$game){
            return new Response('Игра не найдена');
        }
        $companyJSON = [
            'id' => $game->getId(),
            'title' => $game->getTitle(),
            'description' => $game->getDescription(),
            'price' => $game->getPrice(),
            'logo' => $game->getLogo()
        ];
        return new JsonResponse($companyJSON);
    }

    public function createGame (Request $request): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $game = new Game();
        $game->setTitle($request->request->get('title'));
        $game->setDescription($request->request->get('description'));
        $game->setPrice($request->request->get('price'));
        $game->setLogo($request->request->get('logo'));
        $entityManager->persist($game);
        $entityManager->flush();
        return new Response('Игра успешно добавлена, идентификатор: '.$game->getId());
    }

    public function patchGame ($id, Request $request):Response {
        $entityManager = $this->getDoctrine()->getManager();
        $game = $this->getDoctrine()
            ->getRepository(Game::class)
            ->find($id);
        if (!$game) {
            return new Response('Игра не существует');
        } else {
            $game->setTitle($request->request->get('title'));
            $game->setDescription($request->request->get('description'));
            $game->setPrice($request->request->get('price'));
            $game->setLogo($request->request->get('logo'));
            $entityManager->persist($game);
            $entityManager->flush();
            return new Response('Игра была успешно изменена, идентификатор: ' . $game->getId());
        }
    }

    public function deleteGame ($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $game = $entityManager->getRepository(Game::class)->find($id);
        if (!$game) return new Response('Игра не найдена');
        $entityManager->remove($game);
        $entityManager->flush();
        return new Response('Игра с идентификатором '.$id.' была успешно удалена');
    }
}
