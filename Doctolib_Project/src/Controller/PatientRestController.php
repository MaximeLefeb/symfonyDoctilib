<?php

namespace App\Controller;

use App\DTO\PatientDTO;
use App\Entity\Patient;
use App\Mapper\PatientMapper;
use FOS\RestBundle\View\View;
use OpenApi\Annotations as OA;
use App\Service\PatientService;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\PatientRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations\Delete;
use App\Service\Exception\PatientServiceException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @OA\Info(
 *      title="Patient Management",
 *      description="Patient manager (GET,PUT,DELETE,POST)",
 *      version="0.01",
 * )
 */
class PatientRestController extends AbstractFOSRestController {
    private $patientService;
    private $entityManager;
    private $mapper;

    const URI_PATIENT_COLLECTION = "/patients";
    const URI_PATIENT_INSTANCE = "/patient/{id}";

    public function __construct(PatientService $patientService, EntityManagerInterface $entityManager, PatientMapper $mapper) {
        $this->patientService = $patientService ;
        $this->entityManager  = $entityManager ;
        $this->patientMapper  = $mapper ;
    }

    /**
     * @OA\Get(
     *     path="/patients",
     *     tags={"Patient"},
     *     summary="Returns a list of PatientDTO",
     *     description="Returns a list of PatientDTO",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation", 
     *         @OA\JsonContent(ref="#/components/schemas/PatientDTO")   
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="If no PatientDTO found",    
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal server Error. Please contact us",    
     *     )
     * )
     * @Get(PatientRestController::URI_PATIENT_COLLECTION)
     */
    public function searchAll() {
        try {
            $patient = $this->patientService->searchAll();
        } catch (PatientServiceException $e) {
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["content-type" => "application/json"]);
        }

        if ($patient) {
            return View::create($patient, Response::HTTP_OK, ["Content-type" => "application/json"]);
        } else {
            return View::create($patient, Response::HTTP_NOT_FOUND, ["Content-type" => "application/json"]);
        }
    }

    /**
     * @OA\Post(
     *     path="/patients",
     *     tags={"Patient"},
     *     summary="Add a new PatientDTO",
     *     description="Create a object of type PatientDTO",
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
     *                      property="age",
     *                      type="number"
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
     *         @OA\JsonContent(ref="#/components/schemas/PatientDTO")   
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal server Error. Please contact us",    
     *     )
     * ) 
     * @Post(PatientRestController::URI_PATIENT_COLLECTION)
     * @ParamConverter("patientDTO", converter="fos_rest.request_body")
     * @return void
     */
    public function create(PatientDTO $patientDTO) {
        try {
            $patient = new Patient();
            $this->patientService->persist($patient, $patientDTO);
            return View::create([], Response::HTTP_CREATED, ["Content-type" => "application/json"]);
        } catch (PatientServiceException $e) {
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }
    }

    /**
     * @OA\Put(
     *     path="/patient/{id}",
     *     tags={"Patient"},
     *     summary="Modify a PatientDTO",
     *     description="Modify a object of type PatientDTO",
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
     *                      property="age",
     *                      type="number"
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
     *         @OA\JsonContent(ref="#/components/schemas/PatientDTO")   
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal server Error. Please contact us",    
     *     )
     * )  
     * @Put(PatientRestController::URI_PATIENT_INSTANCE)
     * @ParamConverter("patientDTO", converter="fos_rest.request_body")
     * @param PatientDTO $patientDTO
     * @return void
     */
    public function update(Patient $patient, PatientDTO $patientDTO) {
        try {
            $this->patientService->persist($patient, $patientDTO);
            return View::create([], Response::HTTP_OK, ["Content-type" => "application/json"]);
        } catch (PatientServiceException $e) {
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }
    }

    /** 
     * @OA\Delete(
     *     path="/patient/{id}",
     *     tags={"Patient"},
     *     summary="Delete a Patient",
     *     description="Delete a object of type Patient",
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
     * @Delete(PatientRestController::URI_PATIENT_INSTANCE)
     * @param [type] $id
     * @return void
     */
    public function remove(Patient $patient) {
        try {
            $this->patientService->delete($patient);
            return View::create([], Response::HTTP_NO_CONTENT, ["Content-type" => "application/json"]);
        } catch (PatientServiceException $e) {
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }
    }

    /** 
     * @OA\Get(
     *   path="/patient/{id}",
     *   tags={"Patient"},
     *   summary="Return information about a Patient",
     *   description="Return information about a Patient",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="number")
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="The praticien",
     *     @OA\JsonContent(ref="#/components/schemas/PatientDTO")
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
     * @Get(PatientRestController::URI_PATIENT_INSTANCE)
     * @return void
     */
    public function searchById(Int $id) {
        try {
            $patientDTO = $this->patientService->searchById($id);
        } catch (PatientServiceException $e) {
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }

        if ($patientDTO) {
            return View::create($patientDTO, Response::HTTP_OK, ["Content-type" => "application/json"]);
        } else {
            return View::create([], Response::HTTP_NOT_FOUND, ["Content-type" => "application/json"]);
        }
    }
}
