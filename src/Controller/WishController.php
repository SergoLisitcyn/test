<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Repository\WishRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


class WishController extends AbstractController
{

    private $wishRepository;

    public function __construct(WishRepository $wishRepository)
    {
        $this->wishRepository = $wishRepository;
    }

    /**
     * @Route("/wish/add", name="add_wish", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $title = $data['title'];
        $price = $data['price'];
        $created = new \DateTime();
        $status = 0;

        if (empty($title) || empty($price)) {
            throw new NotFoundHttpException('No parametrs');
        }

        $this->wishRepository->saveWish($title, $price, $created, $status);

        return new JsonResponse(['status' => 'Wish created'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/wish", name="get_all_wish", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $wishAll = $this->wishRepository->findAll();
        $data = [];

        foreach ($wishAll as $wish) {
            $data[] = [
                'id' => $wish->getId(),
                'title' => $wish->getTitle(),
                'price' => $wish->getPrice(),
                'date' => $wish->getCreated(),
                'status' => $wish->getStatus(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }


    /**
     * @Route("/wish/update/{id}", name="update_wish", methods={"PUT"})
     */
    public function update($id, Request $request)
    {
        $wish = $this->wishRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);
        $created = new \DateTime();

        empty($data['title']) ? true : $wish->setTitle($data['title']);
        empty($data['price']) ? true : $wish->setPrice($data['price']);
        $wish->setCreated($created);
        $wish->setStatus(0);

        $this->wishRepository->updateWish($wish);

        return new Response('Wish updated');
    }

    /**
     * @Route("/wish/delete/{id}", name="delete_wish", methods={"DELETE"})
     */
    public function delete($id)
    {
        $wish = $this->wishRepository->findOneBy(['id' => $id]);

        if (!$wish) {
            return new Response('No wish found for id '.$id);
        }

        $this->wishRepository->removeWish($wish);

        return new Response('Wish deleted');
    }

    /**
     * @Route("/wish/status/{id}", name="status")
     */
    public function statusAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $wish = $em->getRepository(Wish::class)->find($id);

        if (!$wish) {
            return new Response('No wish found for id '.$id);
        }

        if($wish->getStatus() == 1){
            return new Response('Nothing Updated');
        }

        $wish->setStatus(1);
        $em->flush();

        return new Response('New status - Purchased');
    }
}
