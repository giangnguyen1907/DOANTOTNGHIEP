<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<script>
<?php $url_site = sfConfig::get('app_admin_module_web_dir');?>
var url_toolfile = '<?php echo $url_site;?>/kstools/browse.php';
</script>
<div class="sf_admin_form widget-body">
  <?php echo form_tag_for($form, '@ps_cms_articles', array('class' => 'form-horizontal', 'id' => 'ps_cms_articles_form', 'data-fv-addons' => 'i18n')) ?>
    <?php echo $form->renderHiddenFields(true) ?>

    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>

	<?php foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?>

      <?php include_partial('psCmsArticles/form_fieldset', array('ps_cms_articles' => $ps_cms_articles, 'form' => $form, 'fields' => $fields, 'fieldset' => $fieldset)) ?>

    <?php endforeach; ?>

    <?php include_partial('psCmsArticles/form_actions', array('ps_cms_articles' => $ps_cms_articles, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </form>
</div>
