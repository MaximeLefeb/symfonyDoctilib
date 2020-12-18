<?php 

namespace App\tests\Entity;

use App\Entity\Rdv;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Loader\Configurator\validator;

class RdvTest extends KernelTestCase {
    protected function setUp() {
        self::bootKernel();
        $this->validator = self::$container->get("validator");
    }

    protected function getRdv(String $adresse = NULL, \DateTime $dateRdv = NULL) :Rdv {
        $rdv = (new Rdv())->setAdresse($adresse)->setDateRdv($dateRdv);
        return $rdv;
    }

    //* TEST GETTER SETTER ----------------------------------------------
    public function testGetterAndSetterAdresse() :Void {
        $rdv = $this->getRdv('165 Avenue De Bretagne');
        $this->assertEquals('165 Avenue De Bretagne', $rdv->getAdresse());
    }

    public function testGetterAndSetterDateRdv() :Void {
        $rdv = $this->getRdv(null, new \DateTime('18-12-2020 14:15'));
        $this->assertEquals(new \DateTime('18-12-2020 14:15'), $rdv->getDateRdv());
    }

    //* TEST NOT BLANK ---------------------------------------------------
    public function testIsNotBlank() :Void {
        $rdv = $this->getRdv(null, null);
        $errors = $this->validator->validate($rdv);
        $this->assertCount(2, $errors);
    }

    //* TEST IS VALID ----------------------------------------------------
    public function testIsValid() :Void {
        $rdv = $this->getRdv('165 Avenue De Bretagne', new \DateTime('18-12-2020 14:15'));
        $errors = $this->validator->validate($rdv);
        $this->assertCount(0, $errors);
    }
}