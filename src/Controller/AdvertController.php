<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Form\AdvertType;
use App\Repository\AdvertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/advert")
 */
class AdvertController extends AbstractController
{

    /**
     * @Route("/new", name="advert_new")
     * @Route("/edit/{id}", name="advert_edit")
     */
    public function form (Request $request, EntityManagerInterface $manager, Advert $advert = null)
    {
        if ($advert == null){
            $advert = new Advert();
        }

        //dd($advert);
        $form = $this->createForm(AdvertType::class, $advert);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //get Data from not mapped form
            $data = $form->getData();

            $advert->setUser($this->getUser());

            $manager->persist($advert);

            $manager->flush();

            return $this->redirectToRoute('advert_show', ['id' => $advert->getId()]);
        }

        return $this->render('advert/form.html.twig', [
            'form' => $form->createView(),
            'editMode' => $advert->getId() != null
        ]);
    }

    /**
     * @Route("/{id}", name="advert_show")
     */
    public function show(Advert $advert)
    {
        return $this->render("advert/show.html.twig", [
            'advert' => $advert
        ]);
    }

    /**
     * @Route("", name="advert_index")
     */
    public function index(AdvertRepository $advertRepository)
    {
        $adverts = $advertRepository->findAll();

        return $this->render('advert/index.html.twig', [
            'adverts' => $adverts,
        ]);
    }
}
