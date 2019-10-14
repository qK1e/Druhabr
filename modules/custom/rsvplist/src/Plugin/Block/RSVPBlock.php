<?php
/**
 * @file
 * contains \Drupal\rsvplist\Plugin\Block\RSVPBlock
 */

namespace Drupal\rsvplist\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBuilder;
use Drupal\Core\Session\AccountInterface;
use Drupal\rsvplist\Form\RSVPForm;

/**
 * Provides an 'RSVP' List Block
 * @Block(
 *   id = "rsvp_block",
 *   admin_label = @Translation("RSVP Block"),
 *   category=@Translation("RSVP List")
 * )
 */
class RSVPBlock extends BlockBase {

  /**
   * {@inheritDoc}
   */
  public function build() {
    return \Drupal::formBuilder()->getForm(RSVPForm::class);
  }

  public function blockAccess(AccountInterface $account) {
    /** @var \Drupal\node\Entity\Node $node*/
    $node = \Drupal::routeMatch()->getParameter('node');
    $nid = $node->nid->value;

    /** @var \Drupal\rsvplist\EnablerService $enabler */
    $enabler = \Drupal::service('rsvplist.enabler');

    if(is_numeric($nid)) {
      if($enabler->isEnabled($node)) {
        return AccessResult::allowedIfHasPermission($account, 'view rsvplist');
      }
    }
    return AccessResult::forbidden();
  }

}

