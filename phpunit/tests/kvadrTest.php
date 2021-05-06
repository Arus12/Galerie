<?php
/* @author Jan Černý */
/* Přízak na test -------> vendor/bin/phpunit --verbose tests */
declare(strict_types=1);

use BasicPHPUnitTest\math\kvadr;

final class kvadrTest extends PHPUnit\Framework\TestCase
{

    /* Funkce testsetteruagetteru, který kontroluje, zdali správně fungují gettery a settery */
    public function testsetteruagetteru()
    {
        $kvadr = new kvadr(1, -1, 20);
        $this->assertSame($kvadr->getA(), 1);
        $this->assertSame($kvadr->getB(), -1);
        $this->assertSame($kvadr->getC(), 20);
        $this->assertSame($kvadr->getD(), (pow($kvadr->getB(), 2)-4*$kvadr->getA()* $kvadr->getC()));
        $kvadr = new kvadr(5, 10, 2);
        $this->assertSame($kvadr->getA(), 5);
        $this->assertSame($kvadr->getB(), 10);
        $this->assertSame($kvadr->getC(), 2);
        $this->assertSame($kvadr->getD(), (pow($kvadr->getB(), 2)-4*$kvadr->getA()* $kvadr->getC()));
    }

    /* Funkce testcheckD, která kontroluje, zdali funkce check D funguje správně */
    public function testcheckD(){
        $kvadr = new kvadr(1, -1, -20);
        if($kvadr->getD() > 0){
            $this->assertSame($kvadr->checkD(), NULL);
        }else{
            $this->assertSame($kvadr->checkD(), "Diskriminant není správný");
        }
    }

    /* Funkce testshowRoots, která kontroluje, zdali funkce showRoots počítá výsledky správně */
    public function testshowRoots(){
        $kvadr = new kvadr(1, -1, -20);
        $this->assertSame($kvadr->showRoots(), (-$kvadr->getB() + sqrt($kvadr->getD())) / (2 * $kvadr->getA()).(-$kvadr->getB() - sqrt($kvadr->getD())) / (2 * $kvadr->getA()));
    }
    
    
}
