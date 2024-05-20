<div id="jobs">
  <?php foreach ($categories as $category) : ?>
    <div class="category_<?php echo Jobeet::slugify($category->getName()) ?>">
      <div class="category">
        <div class="feed">
          <a href="">Feed</a>
        </div>
        <h1><?php echo link_to($category, 'category', $category) ?></h1>
      </div>
    </div>

    <table class="jobs">
      <?php $max = sfConfig::get('app_max_jobs_on_homepage') ?>
      <?php foreach ($category->getActiveJobs($max) as $i => $job) : ?>
        <tr class="<?php echo fmod($i, 2) ? 'even' : 'odd' ?>">
          <td class="location">
            <?php echo $job->getLocation() ?></td>
          <td class="position">
            <?php echo link_to($job->getPosition(), 'job_show_user', $job) ?>
          </td>
          <td class="company">
            <?php echo $job->getCompany() ?></td>
        </tr>
      <?php endforeach ?>
    </table>
    <?php $count = $category->countActiveJobs() - sfConfig::get('app_max_jobs_on_homepage') ?>
    <?php if ($count > 0) : ?>
      <div class="more_jobs"> 
        and <?php echo link_to($count, 'category', $category) ?>
        more...
      </div>
    <?php endif ?>
  <?php endforeach ?>
</div>