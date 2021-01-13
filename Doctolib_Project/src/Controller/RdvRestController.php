<?php 

namespace App\Controller;

use App\DTO\RdvDTO;
use App\Entity\Rdv;
use App\Mapper\RdvMapper;
use App\Service\RdvService;
use FOS\RestBundle\View\View;
use App\Controller\RdvRestController;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Exception\RdvServiceException;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


/**
 * @OA\Info(
 *      title="Rdv Management",
 *      description="Rendez-vous manager (GET,PUT,DELETE,POST)",
 *      version="0.01",
 * )
 */
class RdvRestController extends AbstractFOSRestController {
    private $rdvService;
    private $entityManager;
    private $rdvMapper;

    const URI_RDV_COLLECTION         = "/rdvs";
    const URI_RDV_INSTANCE           = "/rdv/{id}";
    const URI_RDV_PATIENT_INSTANCE   = "/rdv/patient/{idPatient}";
    const URI_RDV_PRATICIEN_INSTANCE = "/rdv/praticien/{idPraticien}";

    public function __construct(RdvService $rdvService, EntityManagerInterface $entityManager, RdvMapper $mapper) {
        $this->rdvService       = $rdvService;
        $this->entityManager    = $entityManager;
        $this->rdvServiceMapper = $mapper;
    }

    /**
     * @OA\Get(
     *     path="/rdvs",
     *     tags={"Rdv"},
     *     summary="Returns a list of RdvDTO",
     *     description="Returns a list of RdvDTO",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation", 
     *         @OA\JsonContent(ref="#/components/schemas/RdvDTO")   
     *     ),
     *      @OA\Response(
     *         response=404,
     *         description="If no RdvDTO found",    
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal server Error. Please contact us",    
     *     )
     * ) 
     * @Get(RdvRestController::URI_RDV_COLLECTION)
     */
    public function searchAll() {
        try {
            $rdvs = $this->rdvService->searchAll();
        } catch(RdvServiceException $e){
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }

        if($rdvs) {
            return View::create($rdvs, Response::HTTP_OK, ["Content-type" => "application/json"]);
        } else {
            return View::create($rdvs, Response::HTTP_NOT_FOUND, ["Content-type" => "application/json"]);
        }
    }

    /**
     * @OA\Post(
     *     path="/rdvs",
     *     tags={"Rdv"},
     *     summary="Add a new RdvDTO",
     *     description="Create a object of type RdvDTO",
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="dateRdv",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="adresse",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="patient",
     *                      type="number"
     *                  ),
     *                  @OA\Property(
     *                      property="praticien",
     *                      type="number"
     *                  ),
     *                  example={"dateRdv": "2021-01-12T10:46:59+01:00", "adresse": "13 Boulevard de l'exemple", "patient": 0, "praticien": 0}
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
     * @Post(RdvRestController::URI_RDV_COLLECTION)
     * @ParamConverter("rdvDTO", converter="fos_rest.request_body")
     * @return void
     */
    public function create(RdvDTO $rdvDTO) {
        try {
            $rdv = new Rdv();
            $this->rdvService->persist($rdv, $rdvDTO);
            return View::create([], Response::HTTP_CREATED, ["Content-type" => "application/json"]);
        } catch (PraticienServiceException $e) {
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }
    }

    /**
     * @OA\Delete(
     *     path="/rdv/{id}",
     *     tags={"Rdv"},
     *     summary="Delete a Rdv",
     *     description="Delete a object of type Rdv",
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
     * @Delete(RdvRestController::URI_RDV_INSTANCE)
     * @param [type] $id
     * @return void
     */
    public function remove(Rdv $rdv) {
        try {
            $this->rdvService->delete($rdv);
            return View::create([], Response::HTTP_NO_CONTENT, ["Content-type" => "application/json"]);
        } catch(RdvServiceException $e){
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }
    }

    /**
     * @OA\Get(
     *   path="/rdv/{id}",
     *   tags={"Rdv"},
     *   summary="Return a RdvDTO object",
     *   description="Return information about a RdvDTO",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="number")
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="The Rdv",
     *     @OA\JsonContent(ref="#/components/schemas/RdvDTO")
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
     * @Get(RdvRestController::URI_RDV_INSTANCE)
     * @return void
     */
    public function searchById(int $id) {
        try {
            $rdvDTO = $this->rdvService->searchById($id);
        }catch (RdvServiceException $e){
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }

        if($rdvDTO){
            return View::create($rdvDTO, Response::HTTP_OK, ["Content-type" => "application/json"]);
        } else {
            return View::create([], Response::HTTP_NOT_FOUND, ["Content-type" => "application/json"]);
        }
    }

    /**
     * @OA\Get(
     *   path="/rdv/patient/{id}",
     *   tags={"Rdv"},
     *   summary="Return a list of RdvDTO from a patient id",
     *   description="Return information about a RdvsDTO",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="number")
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Rdvs from a patient id",
     *     @OA\JsonContent(ref="#/components/schemas/RdvDTO")
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
     * @Get(RdvRestController::URI_RDV_PATIENT_INSTANCE)
     * @return void
     */
    public function searchRdvByIdPatient(int $idPatient) {
        try {
            $rdvDTO = $this->rdvService->searchByIdPatient($idPatient);
        }catch (RdvServiceException $e){
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }

        if($rdvDTO){
            return View::create($rdvDTO, Response::HTTP_OK, ["Content-type" => "application/json"]);
        } else {
            return View::create([], Response::HTTP_NOT_FOUND, ["Content-type" => "application/json"]);
        }
    }

    /**
     * @OA\Get(
     *   path="/rdv/praticien/{id}",
     *   tags={"Rdv"},
     *   summary="Return a list of RdvDTO from a praticien id",
     *   description="Return information about a RdvsDTO",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="number")
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Rdvs from a praticien id",
     *     @OA\JsonContent(ref="#/components/schemas/RdvDTO")
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
     * @Get(RdvRestController::URI_RDV_PRATICIEN_INSTANCE)
     * @return void
     */
    public function searchRdvByIdPraticien(int $idPraticien) {
        try {
            $rdvDTO = $this->rdvService->searchByIdPraticien($idPraticien);
        }catch (RdvServiceException $e){
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-type" => "application/json"]);
        }

        if($rdvDTO){
            return View::create($rdvDTO, Response::HTTP_OK, ["Content-type" => "application/json"]);
        } else {
            return View::create([], Response::HTTP_NOT_FOUND, ["Content-type" => "application/json"]);
        }
    }
}