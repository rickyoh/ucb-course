<?php

namespace Drupal\ucb_course\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for UCB Class edit forms.
 *
 * @ingroup ucb_course
 */
class UCBClassEntityForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\ucb_course\Entity\UCBClassEntity */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        \Drupal::messenger()->addStatus($this->t('Created the %label UCB Class.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        \Drupal::messenger()->addStatus($this->t('Saved the %label UCB Class.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.ucb_class_entity.canonical', ['ucb_class_entity' => $entity->id()]);
  }

}
