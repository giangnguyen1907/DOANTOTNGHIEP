<?php use_helper('I18N') ?>
<script type="text/javascript">
	$(document).ready(function(){
  		$("#signin_username").focus();
	});
</script>
<?php echo $form->renderGlobalErrors() ?>
<form action="<?php echo url_for('@sf_guard_signin') ?>" method="post">
<?php echo $form->renderHiddenFields(false) ?>
	<table>
		<tr>
			<td><label><?php echo $form['username']->renderLabelName()?></label></td>
		    <td><?php echo $form['username']->renderError()?><?php echo $form['username']->render()?></td>
		</tr>
		<tr>
			<td><?php echo $form['password']->renderLabel()?></td>
		    <td><?php echo $form['password']->renderError()?><?php echo $form['password']->render()?></td>
		</tr>
		<tr>
			<td></td>
		    <td>
		    <div class="ck"><?php echo $form['remember']->render()?><?php echo __('Remember me');?></>
		    
		    <?php $routes = $sf_context->getRouting()->getRoutes() ?>
			  <?php if (isset($routes['sf_guard_forgot_password'])): ?>
			    <a href="<?php echo url_for('@sf_guard_forgot_password') ?>"><?php echo __('Forgot your password?', null, 'sf_guard') ?></a>
			  <?php endif; ?>
			
			  <?php if (isset($routes['sf_guard_register'])): ?>
			    &nbsp; <a href="<?php echo url_for('@sf_guard_register') ?>"><?php echo __('Want to register?', null, 'sf_guard') ?></a>
			  <?php endif; ?>
		    
		    </td>
		</tr>
	</table>
	
	<div style="margin-top:10px;" class="button"><input type="submit" class="button" value="<?php echo __('Log in') ?>" /></div>
</form>