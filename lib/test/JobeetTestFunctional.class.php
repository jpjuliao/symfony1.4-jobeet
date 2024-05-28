<?php

class JobeetTestFunctional extends sfTestFunctional
{
  public function createJob($values = array(), $publish = false)
  {
    $this->get('/job/new')->click('Preview your job', array('job' => array_merge(array(
        'company'      => 'Sensio Labs',
        'url'          => 'http://www.sensio.com/',
        'position'     => 'Developer',
        'location'     => 'Atlanta, USA',
        'description'  => 'You will work with symfony to develop websites for our customers.',
        'how_to_apply' => 'Send me an email',
        'email'        => 'for.a.job@example.com',
        'is_public'    => false,
      ), $values)))->followRedirect();

    if ($publish) {
      $this->click('Publish', array(), array('method' => 'put', '_with_csrf' => true))->followRedirect();
    }

    return $this;
  }

  public function getJobByPosition($position)
  {
    $q = Doctrine_Query::create()
      ->from('JobeetJob j')
      ->where('j.position = ?', $position);

    return $q->fetchOne();
  }

  public function loadData()
  {
    Doctrine_Core::loadData(sfConfig::get('sf_test_dir') . '/fixtures');

    return $this;
  }

  public function getMostRecentProgrammingJob()
  {
    $q = Doctrine_Query::create()
      ->select('j.*')
      ->from('JobeetJob j')
      ->leftJoin('j.JobeetCategory c')
      ->where('c.slug = ?', 'programming');
    $q = Doctrine_Core::getTable('JobeetJob')->addActiveJobsQuery($q);

    return $q->fetchOne();
  }

  public function getExpiredJob()
  {
    $q = Doctrine_Query::create()
      ->from('JobeetJob j')
      ->where('j.expires_at < ?', date('Y-m-d', time()));

    return $q->fetchOne();
  }
}

// test/functional/frontend/jobActionsTest.php
include(dirname(__FILE__) . '/../../bootstrap/functional.php');

$browser = new JobeetTestFunctional(new sfBrowser());
$browser->loadData();

$browser->info('1 - The homepage')->get('/')->with('request')->begin()->isParameter('module', 'job')->isParameter('action', 'index')->end()->with('response')->begin()->info('  1.1 - Expired jobs are not listed')->checkElement('.jobs td.position:contains("expired")', false)->end();

$max = sfConfig::get('app_max_jobs_on_homepage');

$browser->info('1 - The homepage')->info(sprintf('  1.2 - Only %s jobs are listed for a category', $max))->with('response')->checkElement('.category_programming tr', $max);

$browser->info('1 - The homepage')->get('/')->info('  1.3 - A category has a link to the category page only if too many jobs')->with('response')->begin()->checkElement('.category_design .more_jobs', false)->checkElement('.category_programming .more_jobs')->end();

$browser->info('1 - The homepage')->info('  1.4 - Jobs are sorted by date')->with('response')->begin()->checkElement(sprintf('.category_programming tr:first a[href*="/%d/"]', $browser->getMostRecentProgrammingJob()->getId()))->end();

$job = $browser->getMostRecentProgrammingJob();

$browser->info('2 - The job page')->get('/')->info('  2.1 - Each job on the homepage is clickable and give detailed information')->click('Web Developer', array(), array('position' => 1))->with('request')->begin()->isParameter('module', 'job')->isParameter('action', 'show')->isParameter('company_slug', $job->getCompanySlug())->isParameter('location_slug', $job->getLocationSlug())->isParameter('position_slug', $job->getPositionSlug())->isParameter('id', $job->getId())->end()->info('  2.2 - A non-existent job forwards the user to a 404')->get('/job/foo-inc/milano-italy/0/painter')->with('response')->isStatusCode(404)->info('  2.3 - An expired job page forwards the user to a 404')->get(sprintf('/job/sensio-labs/paris-france/%d/web-developer', $browser->getExpiredJob()->getId()))->with('response')->isStatusCode(404);

// test/functional/frontend/categoryActionsTest.php
include(dirname(__FILE__) . '/../../bootstrap/functional.php');

$browser = new JobeetTestFunctional(new sfBrowser());
$browser->loadData();

$browser->info('1 - The category page')->info('  1.1 - Categories on homepage are clickable')->get('/')->click('Programming')->with('request')->begin()->isParameter('module', 'category')->isParameter('action', 'show')->isParameter('slug', 'programming')->end()->info(sprintf('  1.2 - Categories with more than %s jobs also have a "more" link', sfConfig::get('app_max_jobs_on_homepage')))->get('/')->click('27')->with('request')->begin()->isParameter('module', 'category')->isParameter('action', 'show')->isParameter('slug', 'programming')->end()->info(sprintf('  1.3 - Only %s jobs are listed', sfConfig::get('app_max_jobs_on_category')))->with('response')->checkElement('.jobs tr', sfConfig::get('app_max_jobs_on_category'))->info('  1.4 - The job listed is paginated')->with('response')->begin()->checkElement('.pagination_desc', '/32 jobs/')->checkElement('.pagination_desc', '#page 1/2#')->end()->click('2')->with('request')->begin()->isParameter('page', 2)->end()->with('response')->checkElement('.pagination_desc', '#page 2/2#');
