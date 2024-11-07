<?php
use CRM_Reportsandcustomsearch_ExtensionUtil as E;

class CRM_Reportsandcustomsearch_Form_Report_WeightedVotes extends CRM_Report_Form {

  public function __construct() {
    $this->_columns = [
      'civicrm_dummy_entity' => [
        'fields' => $this->getReportColumns(),
        'filters' => $this->getReportFilters(),
      ]
    ];

    parent::__construct();
  }

  private function getReportColumns() {
    $cols = [];

    $colTitles = [
      'id' => 'Contact ID',
      'display_name' => 'Naam',
    ];

    $i = 1;
    foreach ($colTitles as $k => $colTitle) {
      $cols[$k] = [
        'title' => $colTitle,
        'required' => TRUE,
        'dbAlias' => '1',
      ];

      $i++;
    }

    return $cols;
  }

  private function getReportFilters() {
    return [];
  }

  function preProcess() {
    $this->assign('reportTitle', 'Weighted votes');
    parent::preProcess();
  }

  function from() {
    // take small table
    $this->_from = "FROM civicrm_domain {$this->_aliases['civicrm_dummy_entity']} ";
  }

  function selectClause(&$tableName, $tableKey, &$fieldName, &$field) {
    return parent::selectClause($tableName, $tableKey, $fieldName, $field);
  }

  public function whereClause(&$field, $op, $value, $min, $max) {
    return '';
  }

  public function alterDisplay(&$rows) {
    // build the report from scratch
    $rows = [];

    $referenceYear = date('Y'); // TODO: get from parameter in filter
    $members = new CRM_Reportsandcustomsearch_Members($referenceYear);
    foreach ($members->get() as $member) {
      $row = [];

      $row['civicrm_dummy_entity_id'] = $members['contact_id'];
      $row['civicrm_dummy_entity_display_name'] = $members['contact_id.display_name'];

      $rows[] = $row;
    }
  }
}
