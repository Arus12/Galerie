<?php

namespace BasicPHPUnitTest\math;

/* @author Jan Černý */
/*Class kvadr pro výpočet kvadratické rovnice */

class kvadr
{
    private $a;
    private $b;
    private $c;
    private $d;
    private $x;
    private $y;

    /* Funkce konstrukt pro set všech čísel */
    function __construct(int $a, int $b, int $c)
    {
        $this->setA($a);
        $this->setB($b);
        $this->setC($c);
        $this->setD();
    }

    /* Funkce set pro číslo a */
    public function setA($cislo)
    {
        $this->a = intval($cislo);
        return $this;
    }

    /* Funkce get pro číslo a */
    public function getA()
    {
        return $this->a;
    }

    /* Funkce set pro číslo b */
    public function setB($cislo)
    {
        $this->b = intval($cislo);
        return $this;
    }

    /* Funkce get pro číslo b */
    public function getB()
    {
        return $this->b;
    }

    /* Funkce set pro číslo c */
    public function setC($cislo)
    {
        $this->c = intval($cislo);
        return $this;
    }

    /* Funkce get pro číslo c */
    public function getC()
    {
        return $this->c;
    }

    /* Funkce set pro diskriminant */
    public function setD()
    {
        $this->d = (pow($this->b, 2) - 4 * $this->a * $this->c);
        return $this;
    }

    /* Funkce get pro diskriminant */
    public function getD()
    {
        return $this->d;
    }

    /* Funkce checkD, která kontroluje, zdali diskriminant je možný */
    public function checkD()
    {
        if ($this->a == 0) {
            exit("Diskriminant není správný");
        }
        if ($this->d > 0) {
        } else {
            exit("Diskriminant není správný");
        }
    }

    /* Funkce showRoots, která počítá diskriminant */
    public function showRoots()
    {
        $this->x = (-$this->b + sqrt($this->d)) / (2 * $this->a);
        $this->y = (-$this->b - sqrt($this->d)) / (2 * $this->a);
        return $this->x . $this->y;
    }

    /* Funkce checkresult, která kontroluje, zdali je výsledek možný */
    public function checkresult()
    {
        if ($this->x and $this->y > 0) {
            return ("x1 = " . $this->x . " x2 = " . $this->y);
        } elseif ($this->x > 0) {
            return ("x = " . $this->x);
        } elseif ($this->y > 0) {
            return ("x = " . $this->y);
        } else {
            exit("Výsledek kvadratické rovnice není možný");
        }
    }
}
