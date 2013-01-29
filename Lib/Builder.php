<?php

require_once 'XylExtensionFilter.php';
require_once 'Post.php';

_define('STDOUT', fopen('php://stdout', 'wb'));

class Builder {

  private $_posts = array();

  private $_layout = '';

  public function __construct() {

    // Init hoa:// protocol
    $core = Hoa\Core::getInstance();
    $core->getProtocol()['In'] = new Hoa\Core\Protocol\Generic('In', dirname(__DIR__) . DS . 'In' . DS);
    $core->getProtocol()['Out'] = new Hoa\Core\Protocol\Generic('Out', dirname(__DIR__) . DS . 'Out' . DS);

    $this->_layout = new Hoa\File\Read('hoa://In/Layout.xyl');
  }

  public function build() {

    $this->reset();
    fwrite(STDOUT, '✔  Output folder prepared' . "\n");

    $this->build_posts();
    fwrite(STDOUT, '✔  Posts built' . "\n");

    $this->build_index();
    fwrite(STDOUT, '✔  Index built' . "\n");
  }

  private function reset() {

    // Empty Out directory before generating
    if(file_exists('hoa://Out')) {

      $out = new Hoa\File\Directory('hoa://Out');
      $out->delete();
    }
    Hoa\File\Directory::create('hoa://Out');
  }

  private function build_posts() {

    // Loop through the directory listing
    $dir = new XylExtensionFilter(new DirectoryIterator('hoa://In/Posts/'));
    foreach ($dir as $item) {

      $post = new Post($item, $this->_layout);
      $post->render();

      $this->_posts[] = $post;
    }
  }

  private function build_index() {

    // Render the index
    $index =  new Hoa\Xyl(
              $this->_layout,
              new Hoa\File\Write('hoa://Out/index.html'),
              new Hoa\Xyl\Interpreter\Html()
            );

    $data = $index->getData();

    foreach($this->_posts as $post) {
      $data->posts[] = array('title' => $post->getTitle(),
                             'url' => $post->getOutputFilename());
    }

    $index->addOverlay('hoa://In/Index.xyl');
    $index->render();
  }

}