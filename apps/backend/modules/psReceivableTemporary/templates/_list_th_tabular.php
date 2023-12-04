<?php slot('sf_admin.current_header') ?>

<th class="sf_admin_text sf_admin_list_th_image text-center">
  <?php echo __('Image', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_student_name text-center">
  <?php echo __('Student name', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_receivable_title text-center">
  <?php echo __('Receivable', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_amount text-center">
    <?php echo __('Amount', array(), 'messages') ?>
</th>

<th class="sf_admin_date sf_admin_list_th_receivable_at text-center">
    <?php echo __('Receivable at', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_note text-center">
    <?php echo __('Note', array(), 'messages') ?>
</th>

<th class="sf_admin_text sf_admin_list_th_updated_by text-center">
  <?php echo __('Updated by', array(), 'messages') ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>