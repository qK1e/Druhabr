<?php
/**
 * @file
 * Contains Drupal\rsvplist\Controller\ReportController
 */

namespace Drupal\rsvplist\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

/**
 * Class ReportController
 *
 * @package Drupal\rsvplist\Controller
 *
 * Controller for RSVP List reports
 */
class ReportController extends ControllerBase {

  /**
   * @return array
   */
  protected function load() {
    $select = Database::getConnection()->select('rsvplist', 'r');
    $select->join('users_field_data', 'u', 'r.uid = u.uid');
    $select->join('node_field_data', 'n', 'r.nid = n.nid');

    $select->addField('u', 'name', 'username');
    $select->addField('n', 'title');
    $select->addField('r', 'mail');

    $entries = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
    return $entries;
  }

  /**
   * Creates report page
   *
   * @return array
   */
  public function report() {
    $content = array();

    $content['message'] = array(
      '#markup' => t('Below is a list of all Event RSVPs')
    );

    $headers = array(
      t("Name"),
      t('Event'),
      t('Email')
    );

    $rows = array();
    foreach ($entries = $this->load() as $entry) {
      $rows[] = array_map('Drupal\Component\Utility\SafeMarkup::checkPlain', $entry);
    }

    $content['table'] = array(
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
      '#empty' => t('No entries provided')
    );
    $content['#cache']['max-age'] = 0;
    return $content;
  }
}
