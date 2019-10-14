<?php
/**
 * @file
 * Contains \Drupal\rsvplist\Form\RSVPSettingsForm
 */

namespace Drupal\rsvplist\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form to configure RSVP List module settings
 */

class RSVPSettingsForm extends ConfigFormBase {

  /**
   * {@inheritDoc}
   */
  protected function getEditableConfigNames() {
    return [
      'rsvplist.settings'
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return 'rsvplist_admin_settings';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $types = node_type_get_names();
    $config = $this->config("rsvplist.settings");

    $form['rsvplist_types'] = array(
      '#type' => 'checkboxes',
      '#title' => 'Content types RSVP List is used for',
      '#default_value' => $config->get('allowed_types'),
      '#options' => $types,
      '#description' => t('I\'m too lazy for this shit:('),
    );
    $form['array_filter'] = array(
      '#type' => 'value',
      '#value' => TRUE
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $allowed_types = array_filter($form_state->getValue("rsvplist_types"));
    sort($allowed_types);
    $this->config('rsvplist.settings')
      ->set('allowed_types', $allowed_types)
      ->save();
    parent::submitForm($form, $form_state);
  }

}
