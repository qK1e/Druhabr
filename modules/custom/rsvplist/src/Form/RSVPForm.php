<?php

/**
 * @file
 * Contains Drupal\rsvplist\Form\RSVPForm
 */
namespace Drupal\rsvplist\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;

/**
 * Class RSVPForm
 * Provides an RSVP Email form
 * @package Drupal\rsvplist\Form
 */
class RSVPForm extends FormBase {

  /**
   * (@inheritDoc)
   */
  public function getFormId() {
    return 'rsvplist_email_form';
  }

  /**
   * Form constructor.
   *
   * @param  array  $form
   *   An associative array containing the structure of the form.
   * @param  \Drupal\Core\Form\FormStateInterface  $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $node = \Drupal::routeMatch()->getParameter("node");
    $nid = $node->nid->value;

    $form["email"] = array(
      '#title' => t('Email Address'),
      '#type' => 'textfield',
      '#size' => 25,
      '#description' => t('We will send updates to these emails'),
      '#required' => TRUE
    );
    $form["submit"] = array(
      '#type' => 'submit',
      '#value' => t('RSVP')
    );
    $form["nid"] = array(
      '#type' => 'hidden',
      '#value' => $nid
    );

    return $form;
  }

  /**
   * (@inheritDoc)
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $value = $form_state->getValue("email");
    if($value == !\Drupal::service('email.validator')->isValid($value)){
      $form_state->setErrorByName('email', t('The email address %mail is not valid', array('%mail' => $value)));
      return;
    }
    $node = \Drupal::routeMatch()->getParameter('node');
    // Check if email is already set for this node
    $select = Database::getConnection()->select('rsvplist', 'r');
    $select->fields('r', array('nid'));
    $select->condition('nid', $node->id());
    $select->condition('mail', $value);
    $results = $select->execute();
    if(!empty($results->fetchCol())) {
      //we found a row with this nid and email
      $form_state->setErrorByName('email', t('The address %email is already subscribed to this event',array('%mail' => $value)));
    }

  }

  /**
   * (@inheritDoc)
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $user = User::load(\Drupal::currentUser()->id());

    db_insert('rsvplist')->fields( array(
      'mail' => $form_state->getValue('email'),
      'nid' => $form_state->getValue('nid'),
      'uid' => $user->id(),
      'created' => time()
    ))
    ->execute();

    drupal_set_message(t('You are on the list!'));
  }

}
