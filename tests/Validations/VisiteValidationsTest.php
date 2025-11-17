<?php

namespace App\Tests\Validations;

use App\Entity\Visite;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Description of VisiteValidationsTest
 *
 * @author Patrick
 */
class VisiteValidationsTest extends KernelTestCase {
    public function getVisite() : Visite {
        return (new Visite())
                ->setVille("New York")
                ->setPays("USA");
    }
    
    public function assertErrors(Visite $visite, int $nbErreursAttendues, string $message="") {
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($visite);
        $this->assertCount($nbErreursAttendues, $error, $message);
    }
    
    public function testValidNoteVisite() {
        $notes = [10, 0, 20, null];
        foreach ($notes as $note) {
            $visite = $this->getVisite()->setNote($note);
            $this->assertErrors($visite, 0, "note=$note devrait réussir");
        }
    }
    
    public function testNonValidNoteVisite() {
        $notes = [-1, 21, -100, 100];
        foreach ($notes as $note) {
            $visite = $this->getVisite()->setNote($note);
            $this->assertErrors($visite, 1, "note=$note devrait échouer");
        }
    }
    
    public function testValidTempmaxVisite() {
        $maxtemps = [21, 50];
        foreach ($maxtemps as $maxtemp) {
            $visite = $this->getVisite()
                ->setTempmin(20)
                ->setTempmax($maxtemp);
            $this->assertErrors($visite, 0, "min=20, max=$maxtemp devrait réussir");
        }
    }
    
    public function testNonValidTempmaxVisite() {
        $maxtemps = [19, 20, -20];
        foreach ($maxtemps as $maxtemp) {
            $visite = $this->getVisite()
                ->setTempmin(20)
                ->setTempmax($maxtemp);
            $this->assertErrors($visite, 1, "min=20, max=$maxtemp devrait échouer");
        }
    }
    
    public function testValidDateCreationVisite() {
        $aujourdhui = new \DateTime('today'); // objet DateTime fixé à aujourd'hui

        $visite = $this->getVisite()->setDatecreation($aujourdhui);
        $this->assertErrors($visite, 0);

        $visite = $this->getVisite()->setDatecreation((clone $aujourdhui)->modify('-1 day'));
        $this->assertErrors($visite, 0);
    }

    public function testNonValidDateCreationVisite() {
        $aujourdhui = new \DateTime('today'); // objet DateTime fixé à aujourd'hui

        $visite = $this->getVisite()->setDatecreation((clone $aujourdhui)->modify('+1 day'));
        $this->assertErrors($visite, 1);

        $visite = $this->getVisite()->setDatecreation((clone $aujourdhui)->modify('+1 year'));
        $this->assertErrors($visite, 1);
    }
}
