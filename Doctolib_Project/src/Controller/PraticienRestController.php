<?php

namespace App\Controller;

use App\DTO\PraticienDTO;
use App\Entity\Praticien;
use FOS\RestBundle\View\View;
use App\Mapper\PraticienMapper;
use App\Service\PraticienService;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\PraticienRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations\Delete;
use App\Service\Exception\PraticienServiceException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @OA\Info(
 *      title="Praticien Management",
 *      description="Praticien manager (GET,PUT,DELETE,POST)",
 *      version="0.01",
 * )
 */
class PraticienRestController extends AbstractFOSRestController {
    private $praticienService;
    private $entityManager;
    private $mapper;

    const URI_PRATICIEN_COLLECTION = "/praticiens";
    const URI_PRATICIEN_INSTANCE = "/praticien/{id}";

    public function __construct(PraticienService $praticienService, EntityManagerInterface $entityManager, PraticienMapper $mapper) {
        $this->praticienService = $praticienService ;
        $this->entityManager    = $entityManager ;
        $this->praticienMapper  = $mapper ;
    }

    /**
     * @OA\Get(
     *     path="/praticiens",
     *     tags={"Praticien"},
     *     summary="Returns a list of PraticienDTO",
     *     description="Returns a list of PraticienDTO",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation", 
     *         @OA\JsonContent(ref="#/components/schemas/PatientDTO")   
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="If no PraticienDTO found",    
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal server Error. Please contact us",    
     *     )
     * ) 
     * @Get(PraticienRestController::URI_PRATICIEN_COLLECTION)
     */
    public function searchAll() {
        try {
            $praticien = $this->praticienService->searchAll();
        } catch (PraticienServiceException $e) {
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["content-type" => "application/json"]);
        }

        if ($praticien) {
            return View::create($praticien, Response::HTTP_OK, ["Content-type" => "application/json"]);
        } else {
            return View::create($praticien, Response::HTTP_NOT_FOUND, ["Content-type" => "application/json"]);
        }
    }

    /**
     * @OA\Post(
     *     path="/praticiens",
     *     tags={"Praticien"},
     *     summary="Add a new PraticienDTO",
     *     description="Create a object of type PraticienDTO",
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="email",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="nom",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="prenom",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="specialite",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string"
     *                  ),
     *                  example={"email": "exemple@gmail.com", "nom": "nomExemple", "prenom": "prenomExemple", "age": 0, "password": "pwdExemple"}
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Invalid request body"
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successfully created", 
     *         @OA\JsonContent(ref="#/components/schemas/PraticienDTO")   
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal server Error. Please contact us",    
     *     )
     * )  
     * @Post(PraticienRestController::URI_PRATICIEN_COLLECTION)
     * @ParamConverter("praticienDTO", converter="fos_rest.request_body")
     * @return void
     */
    public function create(PraticienDTO $praticienDTO) {
        try {
            $praticien = new Praticien();
            $this->praticienService->persist($praticien, $praticienDTO);
            return View::create([], Response::HTTP_CREATED, ["Content-type" => "application/json"]);
        } catch (PraticienServiceException $e) {
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }
    }

    /**
     * @OA\Put(
     *     path="/praticien/{id}",
     *     tags={"Praticien"},
     *     summary="Modify a PraticienDTO",
     *     description="Modify a object of type PraticienDTO",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="email",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="nom",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="prenom",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="specialite",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string"
     *                  ),
     *                  example={"email": "exemple@gmail.com", "nom": "nomExemple", "prenom": "prenomExemple", "age": 0, "password": "pwdExemple"}
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Invalid input"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully modified", 
     *         @OA\JsonContent(ref="#/components/schemas/PraticienDTO")   
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal server Error. Please contact us",    
     *     )
     * ) 
     * @Put(PraticienRestController::URI_PRATICIEN_INSTANCE)
     * @ParamConverter("praticienDTO", converter="fos_rest.request_body")
     * @param PraticienDTO $praticienDTO
     * @return void
     */
    public function update(Praticien $praticien, PraticienDTO $praticienDTO) {
        try {
            $this->praticienService->persist($praticien, $praticienDTO);
            return View::create([], Response::HTTP_OK, ["Content-type" => "application/json"]);
        } catch (PraticienServiceException $e) {
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }
    }

    /** 
     * @OA\Delete(
     *     path="/praticien/{id}",
     *     tags={"Praticien"},
     *     summary="Delete a Praticien",
     *     description="Delete a object of type Praticien",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="number")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Successfully deleted"
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal server Error. Please contact us",    
     *     )
     * )  
     * @Delete(PraticienRestController::URI_PRATICIEN_INSTANCE)
     * @param [type] $id
     * @return void
     */
    public function remove(Praticien $praticien) {
        try {
            $this->praticienService->delete($praticien);
            return View::create([], Response::HTTP_NO_CONTENT, ["Content-type" => "application/json"]);
        } catch (PraticienServiceException $e) {
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }
    }

    /**
     * @OA\Get(
     *   path="/praticien/{id}",
     *   tags={"Praticien"},
     *   summary="Return information about a Praticien",
     *   description="Return information about a Praticien",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="number")
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="The praticien",
     *     @OA\JsonContent(ref="#/components/schemas/PraticienDTO")
     *   ),
     *   @OA\Response(
     *     response="500",
     *     description="Internal server Error. Please contact us",
     *   ),
     *   @OA\Response(
     *     response="404",
     *     description="No user found for this id",
     *   )
     * ) 
     * @Get(PraticienRestController::URI_PRATICIEN_INSTANCE)
     * @return void
     */
    public function searchById(Int $id) {
        try {
            $praticienDTO = $this->praticienService->searchById($id);
        } catch (PraticienServiceException $e) {
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }

        if ($praticienDTO) {
            return View::create($praticienDTO, Response::HTTP_OK, ["Content-type" => "application/json"]);
        } else {
            return View::create([], Response::HTTP_NOT_FOUND, ["Content-type" => "application/json"]);
        }
    }
}
