<?php

namespace {

from('Hoa')
-> import('File.Write')
-> import('Xyl.~')
-> import('Xyl.Interpreter.Html.~');

}

namespace Jekxyl\Post {

class Post {

  private $_xyl      = null;

  private $_filename = '';

  public function __construct($item, $layout) {

    $this->_filename = pathinfo($item, PATHINFO_FILENAME);

    $this->_xyl =  new \Hoa\Xyl(
                      $layout,
                      new \Hoa\File\Write('hoa://Application/Out/' . $this->getOutputFilename()),
                      new \Hoa\Xyl\Interpreter\Html()
                    );
    $this->_xyl->addOverlay('hoa://Application/In/Posts/' . $item);
    $this->_xyl->interprete();
  }

  public function render() {

    $this->_xyl->render();
  }

  public function getTitle() {

    // this may be extracted from a meta to be independent of document structure
    return $this->_xyl->xpath("//__current_ns:h1[@id='title']")[0]->readAll();
  }

  public function getOutputFilename() {

    return $this->_filename . '.html';
  }

}

}