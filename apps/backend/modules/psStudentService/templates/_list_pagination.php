<div class="sf_admin_pagination pull-left">
  <ul class="pagination pagination-sm">  
  <li><a href="<?php echo url_for('@ps_student_service') ?>?page=1"><i class="glyphicon glyphicon-step-backward" aria-hidden="true"></i></a></li>

  <li><a href="<?php echo url_for('@ps_student_service') ?>?page=<?php echo $pager->getPreviousPage() ?>"><i class="fa fa-chevron-left" aria-hidden="true"></i></a></li>

  <?php foreach ($pager->getLinks() as $page): ?>
    <li<?php if ($page == $pager->getPage()) echo ' class="active"' ?>><a href="<?php echo url_for('@ps_student_service') ?>?page=<?php echo $page ?>">
      <?php echo $page ?>
    </a></li>
  <?php endforeach; ?>

  <li><a href="<?php echo url_for('@ps_student_service') ?>?page=<?php echo $pager->getNextPage() ?>"><i class="fa fa-chevron-right" aria-hidden="true"></i></a></li>

  <li><a href="<?php echo url_for('@ps_student_service') ?>?page=<?php echo $pager->getLastPage() ?>"><i class="glyphicon glyphicon-step-forward" aria-hidden="true"></i></a></li>
  </ul>
</div>