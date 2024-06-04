<?php

class JobeetAffiliateTable extends Doctrine_Table
{
  public function countToBeActivated()
  {
    $q = $this->createQuery('a')
      ->where('a.is_active = ?', 0);

    return $q->count();
  }
}
