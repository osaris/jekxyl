<?php

namespace {

from('Hoa')
-> import('File.Write')
-> import('Xyl.~')
-> import('Xyl.Interpreter.Html.~')
-> import('Http.Response');

}

namespace Jekxyl\Post {

class Post {

  private $_xyl      = null;

  private $_filename = '';

  private $_metas    = array();

  public function __construct( $file ) {

    $this->_filename = pathinfo($file, PATHINFO_FILENAME);

    $this->extractMetas();

    $this->_xyl =  new \Hoa\Xyl(
                      new \Hoa\File\Read('hoa://Application/In/Layouts/' . $this->getLayoutFileName()),
                      new \Hoa\File\Write('hoa://Application/Out/' . $this->getOutputFilename()),
                      new \Hoa\Xyl\Interpreter\Html()
                    );
    $this->_xyl->addOverlay('hoa://Application/In/Posts/' . $file);
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

  private function getInputFilename() {

    return $this->_filename . '.xyl';
  }

  private function extractMetas() {

    // parse post to extract layout
    $xyl = new \Hoa\Xyl(
        new \Hoa\File\Read('hoa://Application/In/Posts/' . $this->getInputFilename()),
        new \Hoa\Http\Response(),
        new \Hoa\Xyl\Interpreter\Html()
    );

    $ownerDocument = $xyl->readDOM()->ownerDocument;
    $xpath         = new \DOMXpath($ownerDocument);
    $query         = $xpath->query('/processing-instruction(\'xyl-meta\')');

    for($i = 0, $m = $query->length; $i < $m; ++$i) {

        $item    = $query->item($i);
        $meta = new \Hoa\Xml\Attribute($item->data);
        $this->_metas[$meta->readAttribute('name')] = $meta->readAttribute('value');

        // If you would like to remove the PI.
        // Useless for the moment because $xyl isn't the one we render later
        // $ownerDocument->removeChild($item);
    }
  }

  private function getLayoutFileName() {

    $layout_name = empty($this->_metas['layout']) ? 'main' : $this->_metas['layout'];

    return $layout_name . '.xyl';
  }

}

}
