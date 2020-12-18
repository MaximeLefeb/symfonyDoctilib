<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Entity\Praticien;
use App\Form\PatientType;
use App\Form\PraticienType;
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

     /**
     * @Route("/AddPraticien", name="AddPraticien")
     */
    public function addPraticien(EntityManagerInterface $manager, Request $request) :Response {
        $praticien = new Praticien();
        $form = $this->createForm(PraticienType::class, $praticien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $manager->persist($praticien);
                $manager->flush(); 
            } catch (ServiceException $e) {
                return $this->render('main/index.html.twig', [
                    'error' => $e->getMessage(),
                ]);
            } 
            return $this->redirectToRoute('main');
        }

        return $this->render('registration/AddNewPraticien.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
