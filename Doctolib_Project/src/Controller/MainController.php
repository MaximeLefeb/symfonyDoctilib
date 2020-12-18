<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Form\PatientType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController {
    /**
     * @Route("/main", name="main")
     */
    public function index(): Response {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'main',
        ]);
    }

    /**
     * @Route("/AddPatient", name="AddPatient")
     */
    public function addPatient(EntityManagerInterface $manager, Request $request) :Response {
        $patient = new Patient();
        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $manager->persist($patient);
                $manager->flush(); 
            } catch (ServiceException $e) {
                return $this->render('main/index.html.twig', [
                    'error' => $e->getMessage(),
                ]);
            } 
            return $this->redirectToRoute('main');
        }

        return $this->render('registration/AddNewPatient.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
