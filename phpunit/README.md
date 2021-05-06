# Základy iformace o PHPUnit testu a popis spouštění

## Základní iformace

Program počítá kvadratickou rovnici. Na vstupu jsou tři čísla, označena písmenka a, b, c. Program spočítá determinant a popřípadě, kdyby determinant vyšel nevhodně, tak program vypíše chybu a zastaví se. Poté vypočítá výsledek a zkontroluje, zdali je výsledek možný. Kdyby ne, tak napíše chybovou hlášku a kdyby byl možný pouze jeden z výsledků x, tak vypíše pouze ten správný.


## Popis spouštění PHPUnit testu
Program na test je rozdělen do tří částí:
1. Funkce testsetteruagetteru
   - tato funkce kontroluje, zdali všechny gettery a settery fungují, jak mají.
2. Funkce testcheckD
   - tato funkce kontroluje, zdali funkce checkD kontroluje diskriminant správně.
3. Funkce testshowRoots
   - tato funkce kontroluje, zdali jsou výsledky příkladu správné.