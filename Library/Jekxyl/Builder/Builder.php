<?php

namespace {

from('Jekxyl')
-> import('Builder.Xylfilter')
-> import('Post.~');

from('Hoa')
-> import('File.Read')
-> import('File.Write')
-> import('File.Directory')
-> import('Xyl.~')
-> import('Xyl.Interpreter.Html.~');

}

namespace Jekxyl\Builder {

class Builder {

  private $_posts = array();

  private $_layout = '';

  public function __construct() {

    $this->_layout = new \Hoa\File\Read('hoa://Application/In/Layout.xyl');
  }

  public function build() {

    $this->reset();
    echo '✔  Output folder prepared', "\n";

    $this->build_posts();
    echo '✔  Posts built', "\n";

    $this->build_index();
    echo '✔  Index built', "\n";
  }

  private function reset() {

    // Empty Out directory before generating
    if(file_exists('hoa://Application/Out')) {

      $out = new \Hoa\File\Directory('hoa://Application/Out');
      $out->delete();
    }
    \Hoa\File\Directory::create('hoa://Application/Out');
  }

  private function build_posts() {

    // Loop through the directory listing
    $dir = new Xylfilter(new \DirectoryIterator('hoa://Application/In/Posts/'));
    foreach ($dir as $item) {

      $post = new \Jekxyl\Post($item, $this->_layout);
      $post->render();

      $this->_posts[] = $post;
    }
  }

  private function build_index() {

    // Render the index
    $index =  new \Hoa\Xyl(
              $this->_layout,
              new \Hoa\File\Write('hoa://Application/Out/index.html'),
              new \Hoa\Xyl\Interpreter\Html()
            );

    $data = $index->getData();

    foreach($this->_posts as $post) {
      $data->posts[] = array('title' => $post->getTitle(),
                             'url' => $post->getOutputFilename());
    }

    $index->addOverlay('hoa://Application/In/Index.xyl');
    $index->render();
  }

}

}