<?php

require_once '/usr/local/lib/Hoa/Core/Core.php';
require_once 'XylExtensionFilter.php';

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

$layout = new Hoa\File\Read('hoa://In/Layout.xyl');

// Loop through the directory listing
$dir = new XylExtensionFilter(new DirectoryIterator('hoa://In/Posts/'));
foreach ($dir as $item) {

  $filename = pathinfo($item, PATHINFO_FILENAME);
  $xyl =  new Hoa\Xyl(
            $layout,
            new Hoa\File\Write('hoa://Out/'.$filename.'.html'),
            new Hoa\Xyl\Interpreter\Html()
          );
  $xyl->addOverlay('hoa://In/Posts/'.$item);
  $xyl->render();
}
