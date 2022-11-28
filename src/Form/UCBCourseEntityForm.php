<?php

namespace Drupal\ucb_course\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for UCB Course edit forms.
 *
 * @ingroup ucb_course
 */
class UCBCourseEntityForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\ucb_course\Entity\UCBCourseEntity */
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
        \Drupal::messenger()->addStatus($this->t('Created the %label UCB Course.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        \Drupal::messenger()->addStatus($this->t('Saved the %label UCB Course.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.ucb_course_entity.canonical', ['ucb_course_entity' => $entity->id()]);
  }

}
