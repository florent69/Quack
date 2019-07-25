<?php

namespace App\Controller\API;

use App\Entity\Quack;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Repository\QuackRepository;

class ApiController extends AbstractController
{
    //////////////////// QUACK /////////////////////////////////////////////
    /**
     * @Route("/api/quack", name="api_show", methods={"GET"})
     */
    public function showAllQuacks(QuackRepository $quackRepository)
    {
        $data = $quackRepository->findAll();
        $response = new JsonResponse($data);
        return $response;
    }

    /**
     * @Route("/api/quack/{id}", name="api_id_image", methods={"GET"})
     */
    public function showQuack(QuackRepository $quackRepository, $id )
    {
        $data = $quackRepository->findBy(array('id' => $id));
        $result = [];
        foreach($data as $d){
            $result[] = $d->monTableauLight();
        }
        $response = new JsonResponse($result);
        return $response;
    }

    /**
     * @Route("/api/quack/{id}", name="api_delete", methods={"DELETE"})
     */
    public function deleteQuack(QuackRepository $quackRepository, Quack $quack, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($quack, array('id' => $id));
        $entityManager->flush();

        $data = $quackRepository->findAll();
        $response = new JsonResponse($data);
        return $response;
    }

    ///////////////////////////// USER //////////////////////////////////////////////

    /**
     * @Route("/api/user", name="api_register", methods={"POST"})
     */
    public function registerNewUser(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $content = $request->getContent();
        $data = json_decode($content, true);
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $user->setEmail($data['email']);
        $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                $data['password'])
        );
        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);
        $user->setDuckname($data['duckname']);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json($user->jsonSerializeNewUser());
    }

//    /**
//     * @Route("/api/user/{id}", name="api_modify", methods={"PUT"})
//     */
//    public function modifyUser($id)
//    {
//
//    }

}
