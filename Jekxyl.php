<?php

require_once '/usr/local/lib/Hoa/Core/Core.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Lib' . DIRECTORY_SEPARATOR . 'Builder.php';

from('Hoa')
-> import('Xyl.~')
-> import('Xyl.Interpreter.Html.~')
-> import('File.*');

$builder = new Builder();
$builder->build();