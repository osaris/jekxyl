<?php

namespace Jekxyl\Builder {

class Xylfilter extends \FilterIterator {

  // an abstract method which must be implemented in subclass
  public function accept() {
      return $this->getExtension() == 'xyl';
  }
}

}