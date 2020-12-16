<?php 

namespace App\tests\Entity;

use App\Entity\Praticien;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Loader\Configurator\validator;

class PraticienTest extends KernelTestCase {
    private $validator;

    protected function setUp() {
        self::bootKernel();
        $this->validator = self::$container->get("validator");
    }

    private function getPraticien(String $nom = NULL, String $prenom = NULL, String $specialite = NULL) :Praticien {
        $praticien = (new Praticien())->setNom($nom)->setPrenom($prenom)->setSpecialite($specialite);
        return $praticien;
    }

    //* TEST GETTER SETTER ----------------------------------------------------------------------------
    public function testGetterAndSetterNom() :Void {
        $praticien = $this->getPraticien('Lefebvre');
        $this->assertEquals('Lefebvre', $praticien->getNom());
    }

    public function testGetterAndSetterPrenom() :Void {
        $praticien = $this->getPraticien(null, 'Maxime');
        $this->assertEquals('Maxime', $praticien->getPrenom());
    }

    public function testGetterAndSetterSpecialite() :Void {
        $praticien = $this->getPraticien(null, null, 'Kinésithérapeute');
        $this->assertEquals('Kinésithérapeute', $praticien->getSpecialite());
    }

    //* TEST NOT BLANK --------------------------------------------------------------------------------
    public function testNotBlank() :Void {
        $praticien = $this->getPraticien(null, null, null);
        $errors = $this->validator->validate($praticien);
        $this->assertCount(3, $errors);
    }

    //* TEST IS VALID ---------------------------------------------------------------------------------
    public function testIsValid() :Void {
        $praticien = $this->getPraticien('Lefebvre', 'Maxime', 'Kinésithérapeute');
        $errors = $this->validator->validate($praticien);
        $this->assertCount(0, $errors);
    }
}
