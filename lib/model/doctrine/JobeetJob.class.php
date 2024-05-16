<?php

/**
 * JobeetJob
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    jobeet
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
class JobeetJob extends BaseJobeetJob
{
  public function getCompanySlug()
  {
    return Jobeet::slugify($this->getCompany());
  }

  public function getPositionSlug()
  {
    return Jobeet::slugify($this->getPosition());
  }

  public function getLocationSlug()
  {
    return Jobeet::slugify($this->getLocation());
  }
}
