<?php echo false !== strtotime($feature_branch_times->getStartAt()) ? format_date($feature_branch_times->getStartAt(), "dd-MM-yyyy") : '&nbsp;' ?> &rarr; <?php echo false !== strtotime($feature_branch_times->getEndAt()) ? format_date($feature_branch_times->getEndAt(), "dd-MM-yyyy") : '&nbsp;' ?>