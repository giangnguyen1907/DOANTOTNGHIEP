<div class="sf_admin_pagination pull-left">
	<ul class="pagination pagination-sm" id="page-student">
		<li><a href="#page=1" class="page" data-page="1"><i
				class="glyphicon glyphicon-step-backward" aria-hidden="true"></i></a></li>

		<li><a class="page"><i class="fa fa-chevron-left" aria-hidden="true"></i></a></li>

  <?php foreach ($pager->getLinks() as $page): ?>
    <li <?php if ($page == $pager->getPage()) echo ' class="active"' ?>><a
			class="page" data-page="<?php echo $page ?>">
      <?php echo $page ?>
    </a></li>
  <?php endforeach; ?>

  <li><a class="page" data-page="<?php echo $pager->getNextPage() ?>"><i
				class="fa fa-chevron-right" aria-hidden="true"></i></a></li>

		<li><a class="page" data-page="<?php echo $pager->getLastPage() ?>"><i
				class="glyphicon glyphicon-step-forward" aria-hidden="true"></i></a></li>
	</ul>
</div>