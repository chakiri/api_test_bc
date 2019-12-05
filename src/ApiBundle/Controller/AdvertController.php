<?php

namespace App\ApiBundle\Controller;

use App\Entity\Advert;
use App\Form\AdvertType;
use App\Repository\AdvertRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

use Swagger\Annotations as SWG;

/**
 * Advert ApiBundle controller
 */
class AdvertController extends AbstractController
{
    /**
     * List all Advert
     * @Rest\Get("/adverts")
     * @Rest\View()
     *
     * @SWG\Get(
     *     summary="Get adverts",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *     )
     * )
     */
    public function getAdverts(AdvertRepository $advertRepository): View
    {
        $adverts = $advertRepository->findAll();

        return View::create($adverts, Response::HTTP_OK);
    }

    /**
     * Get Advert
     * @Rest\View()
     * @Rest\Get("/advert/{id}")
     */
    public function getAdvert(?Advert $advert): View
    {
        if ($advert){
            return View::create($advert, Response::HTTP_OK);
        }
        return View::create(['message' => 'Advert not found'], Response::HTTP_NOT_FOUND);
    }

    /**
     * Delete Advert
     * @Rest\View()
     * @Rest\Delete("/advert/{id}")
     */
    public function removeAdvert(Request $request, ?Advert $advert, EntityManagerInterface $manager)
    {
        if ($advert) {
            $manager->remove($advert);
            $manager->flush();

            return View::create(['message' => 'Advert removed'], Response::HTTP_OK);

        }

        return View::create(['message' => 'Advert not found'], Response::HTTP_NOT_FOUND);
    }

    /**
     * New Advert
     * @Rest\View()
     * @Rest\Post("/advert")
     *
     * @SWG\Post(
     *     summary="Create advert",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Not found",
     *     ),
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="JSON Payload",
     *          required=true,
     *          format="application/json",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="title", type="string", example="My title"),
     *              @SWG\Property(property="content", type="string", example="My content"),
     *              @SWG\Property(property="category", type="string", example="Emploi, Automobile, Immobilier"),
     *              @SWG\Property(property="attributes", type="json", example="attributes according to category in json"),
     *          )
     *      )
     * )
     */
    public function postAdvert(Request $request, EntityManagerInterface $manager, CategoryRepository $categoryRepository): View
    {
        $advert = new Advert();

        $form = $this->createForm(AdvertType::class, $advert);

        $data = json_decode($request->getContent(),true);

        //Get category by name
        $data["category"] = $categoryRepository->findOneBy(['name' => $request->get('category')])->getId();

        //Keep attributes in format json for validation
        $data["attributes"] = json_encode($data["attributes"]);

        $form->submit($data);

        if ($form->isValid()){

            //$advert->setAttributes($request->get('attributes'));

            $advert->setUser($this->getUser());

            $manager->persist($advert);

            $manager->flush();

            return View::create($advert, Response::HTTP_CREATED);
        }

        return View::create($form->getErrors(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update Advert
     * @Rest\View()
     * @Rest\Put("/advert/{id}")
     *
     * @SWG\Put(
     *     summary="Create advert",
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Not found",
     *     ),
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="JSON Payload",
     *          required=true,
     *          format="application/json",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="title", type="string", example="My title"),
     *              @SWG\Property(property="content", type="string", example="My content"),
     *              @SWG\Property(property="category", type="string", example="Emploi, Automobile, Immobilier"),
     *              @SWG\Property(property="attributes", type="json", example="attributes according to category in json"),
     *          )
     *      )
     * )
     */
    public function updateAdvert(Request $request, EntityManagerInterface $manager, CategoryRepository $categoryRepository, ?Advert $advert): View
    {
        if ($advert){
            $form = $this->createForm(AdvertType::class, $advert);

            $data = json_decode($request->getContent(),true);

            //Get id category by name
            $data["category"] = $categoryRepository->findOneBy(['name' => $request->get('category')])->getId();

            //Keep attributes in format json for validation
            $data["attributes"] = json_encode($data["attributes"]);

            $form->submit($data);

            if ($form->isValid()){

                $advert->setAttributes($request->get('attributes'));

                $manager->persist($advert);

                $manager->flush();

                return View::create($advert, Response::HTTP_CREATED);
            }

            return View::create($form->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        return View::create(['message' => 'Advert not found'], Response::HTTP_NOT_FOUND);

    }
}
