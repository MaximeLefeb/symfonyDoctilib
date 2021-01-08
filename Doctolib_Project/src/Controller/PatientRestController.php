<?php

namespace App\Controller;

use App\DTO\PatientDTO;
use App\Entity\Patient;
use App\Mapper\PatientMapper;
use FOS\RestBundle\View\View;
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
