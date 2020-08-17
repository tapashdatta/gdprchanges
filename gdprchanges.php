<?php

require_once 'gdprchanges.civix.php';
use CRM_Gdprchanges_ExtensionUtil as E;
use CRM_Gdpr_CommunicationsPreferences_Utils as U;

define('GDPRCHANGES_CONTAINER_PREFIX', 'enable_');

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function gdprchanges_civicrm_config(&$config) {
  _gdprchanges_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function gdprchanges_civicrm_xmlMenu(&$files) {
  _gdprchanges_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function gdprchanges_civicrm_install() {
  _gdprchanges_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function gdprchanges_civicrm_postInstall() {
  _gdprchanges_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function gdprchanges_civicrm_uninstall() {
  _gdprchanges_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function gdprchanges_civicrm_enable() {
  _gdprchanges_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function gdprchanges_civicrm_disable() {
  _gdprchanges_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function gdprchanges_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _gdprchanges_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function gdprchanges_civicrm_managed(&$entities) {
  _gdprchanges_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function gdprchanges_civicrm_caseTypes(&$caseTypes) {
  _gdprchanges_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function gdprchanges_civicrm_angularModules(&$angularModules) {
  _gdprchanges_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function gdprchanges_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _gdprchanges_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function gdprchanges_civicrm_entityTypes(&$entityTypes) {
  _gdprchanges_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_thems().
 */
function gdprchanges_civicrm_themes(&$themes) {
  _gdprchanges_civix_civicrm_themes($themes);
}

/**
 * Implements hook_civicrm_pre().
 */
function gdprchanges_civicrm_pre($op, $objectName, $id, &$params) {
  if (in_array($op, ['create', 'edit'])
    && in_array($objectName, ['Individual', 'Household', 'Organization'])
  ) {
    _gdprchanges_civicrm_updatePreParams($params, $id);
    _gdprchanges_civicrm_updateUnSubscribe($params);
  }
}

function _gdprchanges_civicrm_updateUnSubscribe(&$params) {
  $settings = U::getSettings()[U::SETTING_NAME];
  if (empty($settings['enable_unsubscribe'])) {
    return;
  }
  $submittedValues = $_REQUEST;
  if (CRM_Utils_Array::value('className', $_REQUEST) == 'CRM_Gdpr_Page_AJAX') {
    $submittedValues = $_REQUEST['preference'];
  }
  if (empty($submittedValues['gdprchnages_unsubscribe'])) {
    return;
  }
  $params['do_not_email'] = 1;
  $params['is_opt_out'] = 1;
}

function _gdprchanges_civicrm_updatePreParams(&$params, $contactId) {
  $submittedValues = $_REQUEST;
  if (CRM_Utils_Array::value('className', $_REQUEST) == 'CRM_Gdpr_Page_AJAX') {
    $submittedValues = $_REQUEST['preference'];
  }
  $containerPrefix = GDPRCHANGES_CONTAINER_PREFIX;
  if (!empty($submittedValues['form_id']) && !empty($submittedValues['submitted'])
    && strpos($submittedValues['form_id'], 'webform_client_form_') !== FALSE
  ) {
    $submittedValues = $submittedValues['submitted']['gdpr_coms_pref']['channels'];
    $containerPrefix = '';
  }
  $preferredComMethods = _gdprchanges_civicrm_getPreferredComMethods();
  $preferrenceMapping = [
    'email' => 'Email',
    'phone' => 'Phone',
    'post' => 'Postal Mail',
    'sms' => 'SMS',
  ];
  $preferredMethods = _gdprchanges_civicrm_getPreOfContact($contactId);
  $updatePreferences = FALSE;
  foreach ($preferrenceMapping as $key => $name) {
    $fieldName = $containerPrefix . $key;
    if (isset($submittedValues[$fieldName])) {
      $updatePreferences = TRUE;
      $value = $submittedValues[$fieldName];
      $prefValue = array_search($name, $preferredComMethods);
      if (strtolower($value) == 'yes') {
        $preferredMethods[$prefValue] = 1;
      }
      else if (strtolower($value) == 'no') {
        // NO means DONOT
        if (isset($preferredMethods[$prefValue])) {
          unset($preferredMethods[$prefValue]);
        }
      }
    }
  }

  if ($updatePreferences) {
    $params['preferred_communication_method'] = $preferredMethods;
  }
}

function _gdprchanges_civicrm_getPreOfContact($contactId) {
  $preferredMethods = [];
  if ($contactId) {
    try {
      $result = civicrm_api3('Contact', 'getvalue', [
        'return' => 'preferred_communication_method',
        'id' => $contactId,
      ]);
      if ($result) {
        foreach ($result as $method) {
          $preferredMethods[$method] = 1;
        }
      }
    }
    catch (Exception $e) {
    }
  }
  return $preferredMethods;
}

function _gdprchanges_civicrm_getPreferredComMethods() {
  $options = civicrm_api3('OptionValue', 'get', [
    'return' => ['name', 'value'],
    'option_group_id' => 'preferred_communication_method',
  ])['values'];
  return array_column($options, 'name', 'value');
}

/**
 * Implements hook_civicrm_buildForm().
 */
function gdprchanges_civicrm_buildForm($formName, $form) {
  if ($formName == 'CRM_Gdpr_Form_CommunicationsPreferences') {
    $form->addElement('checkbox', 'enable_unsubscribe', ts('Show Unsubscribe?'));
    CRM_Core_Region::instance('page-body')->add([
      'template' => 'CRM/GdprChanges/Page/CommunicationsPreferences.tpl'
    ]);
  }

  if (in_array($formName, [
    'CRM_Contribute_Form_Contribution_ThankYou',
    'CRM_Event_Form_Registration_ThankYou',
  ])) {
    switch ($formName) {
      case 'CRM_Contribute_Form_Contribution_ThankYou':
        $contactId = $form->_contactID;
        if (empty($contactId)) {
          $contactId = CRM_Core_Smarty::singleton()->get_template_vars('contactId');
        }
        break;

      case 'CRM_Event_Form_Registration_ThankYou':
        $contactId = $form->_values['participant']['contact_id'];
        break;
    }

    if (!empty($contactId)) {
      $defaults = _gdprchanges_civicrm_setPreferrenceDefaults($contactId);
      $form->setDefaults($defaults);
    }
  }

    if (in_array($formName, [
      'CRM_Contribute_Form_Contribution_ThankYou',
      'CRM_Event_Form_Registration_ThankYou',
      'CRM_Gdpr_Form_UpdatePreference'
    ])) {
      $settings = U::getSettings()[U::SETTING_NAME];
      if (empty($settings['enable_unsubscribe'])) {
        return;
      }

      $element = $form->add('Checkbox', 'gdprchnages_unsubscribe', ts('Unsubscribe Me'));
      $element->unfreeze();
      $groupSettings = $form->get_template_vars('commPrefGroupsetting');
      $groupEleNames = $form->get_template_vars('groupEleNames');

      $groupEleNames[] = 'gdprchnages_unsubscribe';
      $groupSettings['gdprchnages_unsubscribe']['group_description'] = ts('Once Ticked, You will be Removed from all email Communications');

      $form->assign('commPrefGroupsetting', $groupSettings);
      $form->assign('groupEleNames', $groupEleNames);
      $form->assign('groupEleNamesJSON', json_encode($groupEleNames));
      CRM_Core_Region::instance('page-body')->add([
        'template' => 'CRM/GdprChanges/UpdatePreferences.tpl'
      ]);
    }
}

function _gdprchanges_civicrm_setPreferrenceDefaults($contactId) {
  $defaults = [];
  if (!empty($contactId)) {
    $settings = U::getSettings();
    _gdprchanges_civicrm_getContactsPreferrenceDefaults($defaults, $contactId, $settings);
    _gdprchanges_civicrm_getContactsGroupDefaults($defaults, $contactId, $settings);
  }
  return $defaults;
}

function _gdprchanges_civicrm_getContactsPreferrenceDefaults(&$defaults, $contactId, $settings) {
  //Set Channel default values
  $containerPrefix = GDPRCHANGES_CONTAINER_PREFIX;
  $commPrefSettings = $settings[U::SETTING_NAME];
  $communicationPreferenceMapperFields = U::getCommunicationPreferenceMapperField();
  $contactDetails = civicrm_api3('Contact', 'getsingle', ['id' => $contactId]);

  foreach ($commPrefSettings['channels'] as $key => $value) {
    $name  = str_replace($containerPrefix, '', $key);
    if ($value) {
      $comPref = FALSE;
      foreach ($communicationPreferenceMapperFields[$name] as $fieldName) {
        if (!empty($contactDetails[$fieldName])) {
          $comPref = TRUE;
          break;
        }
      }
      $defaults[$key] = $comPref ? 'NO' : 'YES';
    }
  }
}

function _gdprchanges_civicrm_getContactsGroupDefaults(&$defaults, $contactId, $settings) {
  //Set Group default values
  $commPrefGroupsetting = $settings[U::GROUP_SETTING_NAME];
  $groups = U::getGroups();
  foreach ($groups as $group) {
    $container_name = 'group_' . $group['id'];
    if (!empty($commPrefGroupsetting[$container_name]['group_enable'])) {
      $contactGroupDetails = civicrm_api3('GroupContact', 'getCount', [
        'contact_id' => $contactId,
        'group_id' => $group['id'],
        'status' => 'Added',
      ]);

      if (!empty($contactGroupDetails)) {
        $defaults[$container_name] = 1;
      }
    }
  }
}
