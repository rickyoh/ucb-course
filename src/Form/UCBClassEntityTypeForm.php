<?php

namespace Drupal\ucb_course\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class UCBClassEntityTypeForm.
 */
class UCBClassEntityTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $ucb_class_entity_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $ucb_class_entity_type->label(),
      '#description' => $this->t("Label for the UCB Class type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $ucb_class_entity_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\ucb_course\Entity\UCBClassEntityType::load',
      ],
      '#disabled' => !$ucb_class_entity_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $ucb_class_entity_type = $this->entity;
    $status = $ucb_class_entity_type->save();

    switch ($status) {
      case SAVED_NEW:
        \Drupal::messenger()->addStatus($this->t('Created the %label UCB Class type.', [
          '%label' => $ucb_class_entity_type->label(),
        ]));
        break;

      default:
        \Drupal::messenger()->addStatus($this->t('Saved the %label UCB Class type.', [
          '%label' => $ucb_class_entity_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($ucb_class_entity_type->toUrl('collection'));
  }

}
