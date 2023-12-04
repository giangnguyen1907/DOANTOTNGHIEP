<?php use_helper('I18N')?>

<?php echo $form->renderGlobalErrors()?>

<form action="<?php echo url_for('@sf_guard_signin') ?>" method="post"
	id="login-form" class="smart-form client-form">
<?php echo $form->renderHiddenFields(false)?>
	<fieldset>
		<section>
			<label class="label"><?php echo $form['username']->renderLabelName()?></label>
			<label
				class="input <?php if ($form['username']->hasError()) echo 'state-error'; ?>">
				<i class="icon-append fa fa-user"></i>				
				<?php echo $form['username']->render()?>
				<b class="tooltip tooltip-top-right"> <i
					class="fa fa-user txt-color-teal"></i> <?php echo __('Please enter your username')?>
				</b>
			</label>				
				<?php if ($form['username']->hasError()) {?>
				<em for="signin_username" class="invalid"><?php echo __($form['username']->getError())?></em>
				<?php }?>
		</section>

		<section>
			<label class="label"><?php echo $form['password']->renderLabel()?></label>
			<label
				class="input <?php if ($form['password']->hasError()) echo 'state-error'; ?>">
				<i class="icon-append fa fa-lock"></i>
				<?php echo $form['password']->render()?>
				<b class="tooltip tooltip-top-right"> <i
					class="fa fa-lock txt-color-teal"></i> <?php echo __('Please enter your password')?>
				</b>
			</label>
			<?php if ($form['password']->hasError()) {?>
				<em for="signin_password" class="invalid"><?php echo __($form['password']->getError())?></em>
			<?php }?>
			<div class="note">
			  <?php $routes = $sf_context->getRouting()->getRoutes()?>
			  <?php if (isset($routes['sf_guard_forgot_password'])): ?>
			    <a href="<?php echo url_for('@sf_guard_forgot_password') ?>"><?php echo __('Forgot password?', null, 'sf_guard') ?></a>
			  <?php endif; ?>
				
			</div>
		</section>

		<section>
			<label class="checkbox"> <input type="checkbox"
				name="signin[remember]" id="signin_remember"> <i></i><?php echo __('Remember me')?>
			</label>
		</section>
	</fieldset>
	<footer>
		<button type="submit" class="btn btn-default btn-success btn-sm">
			<i class="fa fa-sign-in"></i> <?php echo __('Sign in') ?></button>
	</footer>
</form>