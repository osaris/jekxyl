<?php

class XylExtensionFilter extends FilterIterator
{
  // an abstract method which must be implemented in subclass
  public function accept() {
      return $this->getExtension() == 'xyl';
  }
}