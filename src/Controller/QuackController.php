<?php

namespace App\Controller;

use App\Entity\Quack;
use App\Form\QuackType;
use App\Repository\QuackRepository;
use App\Repository\TagsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\FileUploader;

/**
 * @Route("/quack")
 */
class QuackController extends AbstractController
{
    /**
     * @Route("/", name="quack_index", methods={"GET"})
     */
    public function index(QuackRepository $quackRepository): Response
    {
        return $this->render('quack/index.html.twig', [
            'quacks' => $quackRepository->findBy(["parent"=>null]),
        ]);
    }

    /**
     * @Route("/new", name="quack_new", methods={"GET","POST"})
     * @Route("/{parent}/new", name="quack_new_comment", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader, ?Quack $parent)
    {
        $quack = new Quack();
        $form = $this->createForm(QuackType::class, $quack);

        if($parent){
            $quack->setParent($parent);
            $form->remove("tag")->remove("photo");
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quack->setAuteur($this->getUser());

            if(!$parent){
            /** @var UploadedFile $photoFile */
            $photoFile = $form['photo']->getData();
            if ($photoFile) {
                $photoFileName = $fileUploader->upload($photoFile);
                $quack->setPhoto($photoFileName);
            }}
            $quack->setCreatedAt(new \DateTime("now"));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quack);
            $entityManager->flush();

            return $this->redirectToRoute('quack_index');
        }

        return $this->render('quack/new.html.twig', [
            'quack' => $quack,
            'form' => $form->createView(),

        ]);
    }
    /**
     * @Route("/search", name="quack_search", methods={"GET"})
     */
    public function searchResult(Request $request, QuackRepository $quackRepository): Response
    {
        return $this->render('quack/index.html.twig', [
            'quacks' => $quackRepository->findBySearchBar($request->query->get('mysearch')),
        ]);
    }

    /**
     * @Route("/{id}", name="quack_show", methods={"GET"})
     */
    public function show(Quack $quack): Response
    {
        return $this->render('quack/show.html.twig', [
            'quack' => $quack,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="quack_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Quack $quack): Response
    {
        $form = $this->createForm(QuackType::class, $quack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('quack_index');
        }

        return $this->render('quack/edit.html.twig', [
            'quack' => $quack,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="quack_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Quack $quack): Response
    {
        $this->denyAccessUnlessGranted('delete', $quack);

        if ($this->isCsrfTokenValid('delete'.$quack->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($quack);
            $entityManager->flush();
        }

        return $this->redirectToRoute('quack_index');
    }


}
