<?php

/**
 * @file
 * Contains \Drupal\shorturl\Form\ShorturlForm.
 */
namespace Drupal\shorturl\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class ShorturlForm extends FormBase {


	/**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'shorturl_form';
  }

	/**
   * {@inheritdoc}
   */
	public function buildForm(array $form,FormStateInterface $form_state){
      $form['lgurl'] = [
  			'#type' => 'url',
  			'#title' => $this->t('Long Url'),
  			'#required' => TRUE,
        '#size' => 40,
		];

		//$form['#theme'] = 'node__honda-vehicle__full';

		$form['actions']['#type'] = 'actions';

		$form['actions']['submit'] = [
			'#type' => 'submit',
			'#value' => $this->t('Submit'),
			'#button_type' => 'primary',
		];
		return $form;
	}
	/**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
		$connection = \Drupal::database();
		$field = $form_state->getValues();
    $shortCode = self::generateRandomString();
    $result = $connection->insert('shorturl')
		  ->fields([
		    'sourceurl' => $field['lgurl'],
		    'shorturl' => $shortCode,
		    'created' => \Drupal::time()->getRequestTime(),
		  ])  ->execute();
    $form_state->setRedirect('shorturl.shlist');
  }
  /**
   * {@inheritdoc}
   */
  public static function generateRandomString($length = 6) {

    $chars ='abcdefghijklmnopqrstuvwxyz';
    //$sets = explode('=', $chars);
    $sets = explode('=', $chars);
        $all = null;
        $randString = null;
        foreach ($sets as $value) {
          //$randString .= $set[array_rand(str_split($value))];
          $randString .= $set[array_rand(str_split($value))];
          $all .= $value;
        }
        $all = str_split($all);
        for($i = 0; $i < 5; $i++) {
            $randString .= $all[array_rand($all)];
        }

        $randString = str_shuffle($randString);
        return $randString;
    }
}

 ?>
