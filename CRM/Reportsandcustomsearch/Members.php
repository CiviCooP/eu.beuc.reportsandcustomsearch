<?php

class CRM_Reportsandcustomsearch_Members {
  public $referenceYear;

  public function __construct(int $referenceYear) {
    $this->referenceYear = $referenceYear;
  }

  public function get() {
    return \Civi\Api4\Membership::get(FALSE)
      ->addSelect('id', 'contact_id', 'contact_id.display_name', 'membership_type_id:label')
      ->addWhere('is_primary_member', '=', TRUE)
      ->addWhere('start_date', '<=', $this->referenceYear . '-12-31')
      ->addWhere('end_date', '>=', $this->referenceYear . '-12-31')
      ->addOrderBy('contact_id.display_name', 'ASC')
      ->execute();
  }
}
