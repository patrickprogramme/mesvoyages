<?php

namespace App\Tests;

use App\Entity\Visite;
use PHPUnit\Framework\TestCase;

/**
 * Description of VisiteTest
 *
 * @author Patrick
 */
class VisiteTest extends TestCase {
    public function testgetDatecreationString() {
        $visite = new Visite();
        $visite->setDatecreation(new \DateTime("2025-11-16"));
        $this->assertEquals("16/11/2025", $visite->getDatecreationString());
    }
}
