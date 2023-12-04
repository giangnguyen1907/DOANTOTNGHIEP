<?php
if (! $sf_user->isAuthenticated ()) {
	include '_page_login.php';
} else {
	include '_page_index.php';
}