<table class="jobs">
  <?php foreach ($jobs as $i => $job): ?>
    <tr class="<?php echo fmod($i, 2) ? 'even' : 'odd' ?>">
      <td class="location"><?php echo $job->getLocation(); ?></td>
      <?php if ($sf_user->isAuthenticated() && $sf_user->isSuperAdmin()) { ?>
      	<td class="position"><?php echo link_to($job->getPosition(), 'job_show_user', $job) ?></td>
      <?php }else{ ?>
      	<td class="position"><?php echo link_to($job->getPosition(), 'job_show', $job) ?></td>    
      <?php } ?>	
      <td class="company"><?php echo $job->getCompany() ?></td>
    </tr>
  <?php endforeach; ?>
</table>