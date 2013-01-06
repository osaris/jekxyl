<?php

require_once '/usr/local/lib/Hoa/Core/Core.php';

from('Hoa')
-> import('Xyl.~')
-> import('Xyl.Interpreter.Html.~')
-> import('File.*');

// Init hoa:// protocol

$core = Hoa\Core::getInstance();
$core->getProtocol()['In'] = new Hoa\Core\Protocol\Generic('In', __DIR__ . DS . 'In' . DS);
$core->getProtocol()['Out'] = new Hoa\Core\Protocol\Generic('Out', __DIR__ . DS . 'Out' . DS);

// Empty Out directory before generating
if(file_exists('hoa://Out')) {

  $out = new Hoa\File\Directory('hoa://Out');
  $out->delete();
}
Hoa\File\Directory::create('hoa://Out');

$xyl =  new Hoa\Xyl(
          new Hoa\File\Read('hoa://In/Layout.xyl'),
          new Hoa\File\Write('hoa://Out/index.html'),
          new Hoa\Xyl\Interpreter\Html()
        );

$xyl->addOverlay('hoa://In/Posts/Test.xyl');
$xyl->render();