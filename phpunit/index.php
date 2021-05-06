<?php

use BasicPHPUnitTest\math;

require __DIR__ . "/vendor/autoload.php";

$k = new math\kvadr(1, -1, -20);
echo $k->checkD($k);
echo $k->showRoots($k);
echo $k->checkresult($k);
