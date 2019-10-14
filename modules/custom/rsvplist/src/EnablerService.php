<?php
/**
 * @file
 * Contains Drupal\rsvplist\EnablerService
 */

namespace Drupal\rsvplist;

use Drupal\Core\Database\Database;
use Drupal\node\Entity\Node;

/**
 * Defines a service for managing RSVP list enabled for nodes
 *
 * Class EnablerService
 *
 * @package Drupal\rsvplist\EnablerService
 *
 */
class EnablerService {
  /**
   * Constructor
   */
  public function __construct() {
  }


  /**
   * Set individual node to be RSVP enabled
   *
   * @param  \Drupal\node\Entity\Node  $node
   *
   * @throws \Exception
   */
  public function setEnabled(Node $node){
    if(!$this->isEnabled($node)) {
      $insert = Database::getConnection()->insert('rsvplist_enabled');
      $insert->fields(array('nid'), array($node->id()));
      $insert->execute();
    }
  }

  /**
   * Checks if individual node is RSVP enabled
   *
   * @param  \Drupal\node\Entity\Node  $node
   *
   * @return bool
   *   Whether the node is enabled for the RSVP functionality
   */
  public function isEnabled(Node $node){
    if($node->isNew()) {
      return false;
    }
    $select = Database::getConnection()->select('rsvplist_enabled', 're');
    $select->fields('re', array('nid'));
    $select->condition('nid', $node->id());
    $results = $select->execute();
    return !empty($results->fetchCol());
  }

  /**
   * Deletes enabled settings for an individual node
   *
   * @param  \Drupal\node\Entity\Node  $node
   */
  public function delEnabled(Node $node){
    $delete = Database::getConnection()->delete('rsvplist_enabled');
    $delete->condition('nid', $node->id());
    $delete->execute();
  }
}
