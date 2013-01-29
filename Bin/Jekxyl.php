<?php

require_once '/usr/local/lib/Hoa/Core/Core.php';

$core = Hoa\Core::getInstance();
$core->initialize(array(
    'root.application'        => '(:cwd:h:)/Application',
    'namespace.prefix.Jekxyl' => '(:cwd:h:)/Library/',
    'protocol.Library'        => '(:%namespace.prefix.Jekxyl:)/Jekxyl/;' .
                                 $core->getParameters()->getParameter('protocol.Library'),
));

// If you want to inherit from `hoa`.
//require 'hoa://Library/Core/Bin/Hoa.php';

from('Jekxyl')
-> import('Builder.~');

$builder = new Jekxyl\Builder();
$builder->build();