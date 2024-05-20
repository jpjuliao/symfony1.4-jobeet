<?php

class JobeetJobTable extends Doctrine_Table
{
  public function getActiveJobs()
  {
    $q = $this->createQuery('j')
      ->where('j.expires_at > ?', date('Y-m-d H:i:s', time()))
      ->orderBy('j.expires_at DESC');

    return $q->execute();
  }
}
