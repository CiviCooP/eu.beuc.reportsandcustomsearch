<?php

class CRM_Reportsandcustomsearch_Members {
  public $votingYear;
  public $votingYearMinusOne;

  public function __construct(int $votingYear) {
    $this->votingYear = $votingYear;
    $this->votingYearMinusOne = $votingYear - 1;
  }

  public function get() {
    return \Civi\Api4\Membership::get(FALSE)
      ->addSelect('id', 'contact_id', 'contact_id.display_name', 'membership_type_id:label', 'address.country_id:label')
      ->addJoin('Address AS address', 'LEFT', ['contact_id', '=', 'address.contact_id'], ['address.is_primary', '=', 1])
      ->addWhere('is_primary_member', '=', TRUE)
      ->addWhere('start_date', '<=', $this->votingYear . '-12-31')
      ->addWhere('end_date', '>=', $this->votingYear . '-12-31')
      ->addOrderBy('contact_id.display_name', 'ASC')
      ->execute();
  }

  public function getMembershipFee($contactId) {
    $sql = "
      select 
        ifnull(sum(total_amount), 0) fee,
        contribution_status_id
      from 
        civicrm_contribution 
      where 
        contact_id = $contactId 
      and 
        year(receive_date) = {$this->votingYearMinusOne} 
      and 
        financial_type_id = 2
    ";

    $dao = CRM_Core_DAO::executeQuery($sql);
    if ($dao->fetch()) {
      return [$dao->fee, $dao->contribution_status_id == 1 ? 'Paid' : 'Not paid'];
    }
    else {
      return [0, ''];
    }
  }
}
