<?php

namespace App\Tests\Repository;

use App\Entity\Visite;
use App\Repository\VisiteRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of VisiteRepositoryTest
 *
 * @author Patrick
 */
class VisiteRepositoryTest extends KernelTestCase {
    public const VILLE_TEST = "VilleUniquePourTest1776";

    public function recupRepository(): VisiteRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(VisiteRepository::class);
        return $repository;
    }
    
    public function newVisite(): Visite {
        $visite = (new Visite())
                ->setVille(self::VILLE_TEST)
                ->setPays("USA")
                ->setDatecreation(new DateTime("now"));
        return $visite;
    }
    
    public function testNbVisites() {
        $repository = $this->recupRepository();
        $nbVisites = $repository->count([]);
        $this->assertEquals(101, $nbVisites);
    }
    
    public function testAddVisite(){
        $repository = $this->recupRepository();
        $visite = $this->newVisite();
        $nbVisites = $repository->count([]);
        $repository->add($visite);
        $this->assertEquals($nbVisites + 1, $repository->count([]), "erreur lors de l'ajout");
    }
    
    public function testRemoveVisite(){
        $repository = $this->recupRepository();
        $visite = $this->newVisite();
        $repository->add($visite, true);
        $nbVisites = $repository->count([]);
        $repository->remove($visite, true);
        $this->assertEquals($nbVisites - 1, $repository->count([]), "erreur lors de la suppression");        
    }
    
    public function testFindByEqualValue() {
        $repository = $this->recupRepository();
        $visite = $this->newVisite();
        $repository->add($visite, true);
        $visites = $repository->findByEqualValue("ville", self::VILLE_TEST);
        $nbVisites = count($visites);
        $this->assertEquals(1, $nbVisites);
        $this->assertEquals(self::VILLE_TEST, $visites[0]->getVille());
    }
}
