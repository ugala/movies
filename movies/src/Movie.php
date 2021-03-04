<?php

namespace Drupal\movies;

use \Drupal\node\Entity\Node;

/**
 * A utility service providing functionality related to Commerce Bambora.
 */
class Movie {

  /**
   * Initializes the Beanstream Gateway class and returns it.
   *
   * @param \Drupal\file\Entity\File $file
   *   The CSV file we are uploading
   */
  public function validate($file_id) {
    $file = \Drupal\file\Entity\File::load($file_id);
    $file_url = file_create_url($file->uri->value);
    $handle = @fopen($file_url, "r");
    $content = fgetcsv($handle);

    if(!empty($content)){
      return TRUE;
    }

    return FALSE;
  }

  public function saveMovies($file_id, $ignore=NULL) {
    $file = \Drupal\file\Entity\File::load($file_id);
    $file_url = file_create_url($file->uri->value);
    $handle = @fopen($file_url, "r");
    //$content = fgetcsv($handle);
  
    // $row is an array of elements in each row
    // e.g. if the first column is the email address of the user, try something like

      while ($row = fgetcsv($handle)) {

      if($ignore == 1) {
        $ignore++;
        continue;
      }

      
      $node = Node::create([
        'type'        => 'movies',
        'title'       => $row[2],
        'field_id' => $row[0],
        'field_year' => $row[1]
      ]);
      $node->save();
      
    }
  }
}
