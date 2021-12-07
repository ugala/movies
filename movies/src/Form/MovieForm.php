<?php

namespace Drupal\movies\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\movies\Movie;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MovieForm extends FormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * The returned ID should be a unique string that can be a valid PHP function
   * name, since it's used in hook implementation names such as
   * hook_form_FORM_ID_alter().
   *
   * @return string
   *   The unique string identifying the form.
   */

  public function __construct(
    Movie $movie
  ) {
    $this->movie = $movie;
  }

    /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    return new static(
      // Load the service required to construct this class.
      $container->get('movies.movie')
    );
  }

  public function getFormId() {
    return 'movie_form';
  }

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t('Please upload the CSV.'),
    ];

    
    $validators = array(
        'file_validate_extensions' => array('csv'),
    );

    $form['file_upload'] = array(
      '#type' => 'managed_file',
      '#name' => 'file_upload',
      '#title' => t('File *'),
      '#size' => 20,
      '#description' => t('Upload CSV for movies'),
      '#upload_validators' => $validators,
      '#upload_location' => 'public://my_files/',
    );

    $form['header'] = array(
      '#type' => 'checkbox',
      '#title' => $this
        ->t('Has Header?'),
      '#description' => $this->t('Please check if the csv has an header'),
    );

    $form['actions'] = [
      '#type' => 'actions',
    ];

    // Add a submit button that handles the submission of the form.
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;

  }

   /**
   * Validate form
   * 
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * 
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $file_id = $form_state->getValue('file_upload')[0];

    try {
      $validate = $this->movie->validate($file_id);
      if ($validate == FALSE) {
        $form_state->setErrorByName('file_upload', $this->t('CSV is empty'));
      }
    }
    catch (\Exception $e) {
      $form_state->setErrorByName('file_upload', $this->t('CSV is empty'));
    }
  }

    /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $file_id = $form_state->getValue('file_upload')[0];
    $submit = $this->movie->saveMovies($file_id, $form['header']['#value']);
    drupal_set_message('File Uploaded');
  } 
}