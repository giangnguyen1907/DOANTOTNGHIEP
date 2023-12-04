<?php
echo $feature_branch_times->getUpdatedBy () . '<br/>';
echo false !== strtotime ( $feature_branch_times->getUpdatedAt () ) ? format_date ( $feature_branch_times->getUpdatedAt (), "HH:mm dd/MM/yyyy" ) : '&nbsp;'?>
