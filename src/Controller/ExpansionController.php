<?php


namespace App\Controller;

use App\Entity\Game;
use App\Entity\Expansion;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ExpansionController extends AbstractController
{
    public function getExpansions () {
        $expansions = $this->getDoctrine()
            ->getRepository(Expansion::class)
            ->findAll();
        if (!$expansions){
            return new Response("Таблица пуста!");
        }
        $arrayCollection = array();

        foreach($expansions as $expansion) {
            $arrayCollection[] = array(
                'id' => $expansion->getId(),
                'title' => $expansion->getTitle(),
                'description' => $expansion->getDescription(),
                'price' => $expansion->getPrice(),
                'logo' => $expansion->getLogo(),
                'game' => $expansion->getGame()->getId()
            );
        }

        return new JsonResponse($arrayCollection);
    }

    public function getGameExpansions ($gameId) {
        $game = $this->getDoctrine()
            ->getRepository(Game::class)
            ->find($gameId);
        if (!$game) return new Response('Игра с идентификатором '.$gameId.' не найдена');
        $expansions = $game->getExpansions();
        if (count($expansions)===0) return new Response('Игра '.$game->getName().' не имеет дополнений');
        $arrayCollection = array();

        foreach($expansions as $expansion) {
            $arrayCollection[] = array(
                'id' => $expansion->getId(),
                'title' => $expansion->getTitle(),
                'description' => $expansion->getDescription(),
                'price' => $expansion->getPrice(),
                'logo' => $expansion->getLogo(),
                'game' => $expansion->getGame()->getId()
            );
        }

        return new JsonResponse($arrayCollection);
    }

    public function getExpansion ($id) {
        $expansion = $this->getDoctrine()
            ->getRepository(Expansion::class)
            ->find($id);
        if (!$expansion){
            return new Response('Дополнение не найдено');
        }
        $vacancyJSON = [
            [
                'id' => $expansion->getId(),
                'title' => $expansion->getTitle(),
                'description' => $expansion->getDescription(),
                'price' => $expansion->getPrice(),
                'logo' => $expansion->getLogo(),
                'game' => $expansion->getGame()->getId()
            ]
        ];
        return new JsonResponse($vacancyJSON);
    }

    public function createExpansion (Request $request): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $expansion = new Expansion();
        $gameId = $request->request->get('gameId');
        $game = $this->getDoctrine()
            ->getRepository(Game::class)
            ->find($gameId);
        if(!$game){
            return new Response('Игра не найдена');
        }
        $expansion->setGame($game);
        $expansion->setTitle($request->request->get('title'));
        $expansion->setDescription($request->request->get('description'));
        $expansion->setPrice($request->request->get('price'));
        $expansion->setLogo($request->request->get('logo'));
        $entityManager->persist($expansion);
        $entityManager->flush();
        return new Response('Дополнение с идентификатором '.$expansion->getId().' успешно создано');
    }

    public function patchExpansion ($id, Request $request): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $gameId = $request->request->get('gameId');
        $game = $this->getDoctrine()
            ->getRepository(Game::class)
            ->find($gameId);
        if(!$game){
            return new Response('Игра не найдена');
        }
        $expansion = $this->getDoctrine()
            ->getRepository(Expansion::class)
            ->find($id);
        if(!$expansion){
            return new Response('Дополнение не найдено');
        }
        $expansion->setGame($game);
        $expansion->setTitle($request->request->get('title'));
        $expansion->setDescription($request->request->get('description'));
        $expansion->setPrice($request->request->get('price'));
        $expansion->setLogo($request->request->get('logo'));
        $entityManager->persist($expansion);
        $entityManager->flush();
        return new Response('Дополнение с идентификатором '.$expansion->getId().' успешно изменено');
    }

    public function deleteExpansion ($id): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $expansion = $entityManager->getRepository(Expansion::class)->find($id);
        if (!$expansion) return new Response('Дополнение не найдено');
        $entityManager->remove($expansion);
        $entityManager->flush();
        return new Response('Дополнение с идентификатором '.$id.' было успешно удалено');
    }
}
