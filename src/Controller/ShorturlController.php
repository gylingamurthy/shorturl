<?php

namespace Drupal\shorturl\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Routing\TrustedRedirectResponse;

/**
 * Returns responses for shorturl routes.
 */
class ShorturlController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build = \Drupal::formBuilder()->getForm('Drupal\shorturl\Form\ShorturlForm');

    return $build;
  }
  /**
   * Builds the response.
   */
  public function getlist() {
    $result = \Drupal::database()->select('shorturl', 'n')
            ->fields('n', array('sourceurl', 'shorturl'))
            ->execute()->fetchAll();
// Create the row element.
    $rows = array();
    foreach ($result as $row => $content) {
      $furl = Url::fromUserInput('/view-details/'.$content->shorturl);
      $rows[] = array(
        'data' => array($content->sourceurl, Link::fromTextAndUrl($content->shorturl.'.ly', $furl)->toString()));

     }

// Create the header.
    $header = array('sourceurl', 'shorturl');
    $output = array(
      '#theme' => 'table',    // Here you can write #type also instead of #theme.
      '#header' => $header,
      '#rows' => $rows,
    );
    return $output;
  }
  public function redirecturl($scode , Request $request) {
    $count = 0;
    $result = \Drupal::database()->select('shorturl', 'n')
            ->fields('n', array('sourceurl','noredirect'))
            ->condition('n.shorturl', $scode, '=')
            ->execute()->fetchAll();

    foreach ($result as $row => $content) {
         $ourl = $content->sourceurl;
         $count = $content->noredirect;
    }
    $result = \Drupal::database()->update('shorturl')
        		  ->fields([
        		    'noredirect' => (int)$count+1,
        		    ])->condition('shorturl', $scode, '=')->execute();
    return new TrustedRedirectResponse($ourl);;
  }
}
