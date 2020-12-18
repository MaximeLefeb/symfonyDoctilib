<?php

namespace App\test\Entity;

use App\Entity\Patient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Loader\Configurator\validator;

class PatientTest extends KernelTestCase {
    private $validator;

    protected function setUp() {
        self::bootKernel();
        $this->validator = self::$container->get("validator");
    }

    private function getPatient(String $nom = NULL, String $prenom = NULL, Int $age = NULL) :Patient {
        $patient = (new Patient())->setNom($nom)->setPrenom($prenom)->setAge($age);
        return $patient;
    }

    //* TEST GETTER SETTER ----------------------------------------------------------------------------
    public function testGetterAndSetterNom() :Void {
        $patient = $this->getPatient('Lefebvre');
        $this->assertEquals('Lefebvre', $patient->getNom());
    }

    public function testGetterAndSetterPrenom() :Void {
        $patient = $this->getPatient(null, 'Maxime');
        $this->assertEquals('Maxime', $patient->getPrenom());
    }

    public function testGetterAndSetterAge() :Void {
        $patient = $this->getPatient(null, null, 20);
        $this->assertEquals(20, $patient->getAge());
    }

    //* TEST NOT BLANK --------------------------------------------------------------------------------
    public function testNotBlank() :Void {
        $patient = $this->getPatient(null, null, null);
        $errors = $this->validator->validate($patient);
        $this->assertCount(3, $errors);
    }

    //* TEST IS VALID ---------------------------------------------------------------------------------
    public function testIsValid() :Void {
        $patient = $this->getPatient('Maxime', 'Lefebvre', 25);
        $errors = $this->validator->validate($patient);
        $this->assertCount(0, $errors);
    }
}