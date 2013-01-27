<?php

class Post {

  private $_xyl;
  private $_filename;

  public function __construct($item, $layout) {

    $this->_filename = pathinfo($item, PATHINFO_FILENAME);

    $this->_xyl =  new Hoa\Xyl(
                      $layout,
                      new Hoa\File\Write('hoa://Out/' . $this->_filename . '.html'),
                      new Hoa\Xyl\Interpreter\Html()
                    );
    $this->_xyl->addOverlay('hoa://In/Posts/' . $item);
    $this->_xyl->interprete();
  }

  public function render() {

    $this->_xyl->render();
  }

  public function getTitle() {

    // this may be extracted from a meta to be independent of document structure
    return $this->_xyl->xpath("//__current_ns:h1[@id='title']")[0]->readAll();
  }

  public function getFilename() {

    return $this->_filename;
  }

}