<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Entity\Praticien;
use App\Form\PatientType;
use App\Form\PraticienType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\DriverException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Security\UserAuthentificatorAuthenticator;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
    public function addPatient(EntityManagerInterface $manager, Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UserAuthentificatorAuthenticator $authenticator) :Response {
        $patient = new Patient();
        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $patient->setPassword(
                    $passwordEncoder->encodePassword(
                        $patient,
                        $form->get('password')->getData()
                    )
                );

                $manager->persist($patient);
                $manager->flush(); 

                return $guardHandler->authenticateUserAndHandleSuccess(
                    $patient,
                    $request,
                    $authenticator,
                    'main'
                );

            } catch (DriverException $e) {
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
    public function addPraticien(EntityManagerInterface $manager, Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UserAuthentificatorAuthenticator $authenticator) :Response {
        $praticien = new Praticien();
        $form = $this->createForm(PraticienType::class, $praticien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $praticien->setPassword(
                    $passwordEncoder->encodePassword(
                        $praticien,
                        $form->get('password')->getData()
                    )
                );

                $manager->persist($praticien);
                $manager->flush(); 

                return $guardHandler->authenticateUserAndHandleSuccess(
                    $praticien,
                    $request,
                    $authenticator,
                    'main'
                );
            } catch (DriverException $e) {
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
