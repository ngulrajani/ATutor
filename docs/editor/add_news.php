<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2005 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/
// $Id$
	define('AT_INCLUDE_PATH', '../include/');
	require (AT_INCLUDE_PATH.'vitals.inc.php');

authenticate(AT_PRIV_ANNOUNCEMENTS);

if (defined('AT_FORCE_GET_FILE') && AT_FORCE_GET_FILE) {
	$content_base_href = 'get.php/';
} else {
	$content_base_href = 'content/' . $_SESSION['course_id'] . '/';
}

if (isset($_POST['cancel'])) {
	$msg->addFeedback('CANCELLED');
	header('Location: '.$_base_href.'tools/news/index.php');
	exit;
} 

if ((!$_POST['setvisual'] && $_POST['settext']) || !$_GET['setvisual']){
	$onload = 'document.form.title.focus();';
}

if (isset($_POST['add_news'])&& isset($_POST['submit'])) {
	$_POST['formatting'] = intval($_POST['formatting']);
	
	if (($_POST['title'] == '') && ($_POST['body_text'] == '') && !isset($_POST['setvisual'])) {
		$msg->addError('ANN_BOTH_EMPTY');
	}
	if (!$msg->containsErrors() && (!isset($_POST['setvisual']) || isset($_POST['submit']))) {

		$_POST['formatting']  = $addslashes($_POST['formatting']);
		$_POST['title']  = $addslashes($_POST['title']);
		$_POST['body_text']  = $addslashes($_POST['body_text']);

		$sql	= "INSERT INTO ".TABLE_PREFIX."news VALUES (0, $_SESSION[course_id], $_SESSION[member_id], NOW(), $_POST[formatting], '$_POST[title]', '$_POST[body_text]')";
		mysql_query($sql, $db);
	
		$msg->addFeedback('NEWS_ADDED');

		/* update announcement RSS: */
		if (file_exists(AT_CONTENT_DIR . 'feeds/' . $_SESSION['course_id'] . '/RSS1.0.xml')) {
			@unlink(AT_CONTENT_DIR . 'feeds/' . $_SESSION['course_id'] . '/RSS1.0.xml');
		}
		if (file_exists(AT_CONTENT_DIR . 'feeds/' . $_SESSION['course_id'] . '/RSS2.0.xml')) {
			@unlink(AT_CONTENT_DIR . 'feeds/' . $_SESSION['course_id'] . '/RSS2.0.xml');
		}

		header('Location: '.$_base_href.'tools/news/index.php');
		exit;
	}
}

require(AT_INCLUDE_PATH.'header.inc.php');

if (($_POST['setvisual'] && !$_POST['settext']) || $_GET['setvisual']) {
	load_editor();
}
$msg->printErrors();

?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form">
	<input type="hidden" name="add_news" value="true" />

	<div class="input-form">
		<div class="row">
			<div class="required" title="<?php echo _AT('required_field'); ?>">*</div><label for="title"><?php echo _AT('title'); ?></label><br />
			<input type="text" name="title" size="40" id="title" value="<?php echo $_POST['title']; ?>" />
		</div>

		<div class="row">
			<?php echo _AT('formatting'); ?><br />
			<input type="radio" name="formatting" value="0" id="text" <?php if ($_POST['formatting'] == 0) { echo 'checked="checked"'; } ?> onclick="javascript: document.form.setvisual.disabled=true;" <?php if ($_POST['setvisual'] && !$_POST['settext']) { echo 'disabled="disabled"'; } ?> />

			<label for="text"><?php echo _AT('plain_text'); ?></label>
			<input type="radio" name="formatting" value="1" id="html" <?php if ($_POST['formatting'] == 1 || $_POST['setvisual']) { echo 'checked="checked"'; } ?> onclick="javascript: document.form.setvisual.disabled=false;"/>

			<label for="html"><?php echo _AT('html'); ?></label>
			<?php   //Button for enabling/disabling visual editor
				if (($_POST['setvisual'] && !$_POST['settext']) || $_GET['setvisual']){
					echo '<input type="hidden" name="setvisual" value="'.$_POST['setvisual'].'" />';
					echo '<input type="submit" name="settext" value="'._AT('switch_text').'" />';
				} else {
					echo '<input type="submit" name="setvisual" value="'._AT('switch_visual').'"  ';
					if ($_POST['formatting']==0) { echo 'disabled="disabled"'; }
					echo '/>';
				}
			?>
		</div>

		<div class="row">
			<div class="required" title="<?php echo _AT('required_field'); ?>">*</div><label for="body_text"><?php echo _AT('body'); ?></label><br />
			<textarea name="body_text" cols="55" rows="15" id="body_text"><?php echo $_POST['body_text']; ?></textarea>
		</div>
		
		<div class="row buttons">
			<input type="submit" name="submit" value="<?php echo _AT('save'); ?>" accesskey="s" />
			<input type="submit" name="cancel" value="<?php echo _AT('cancel'); ?> " />
		</div>
	</div>
	</form>

<?php require(AT_INCLUDE_PATH.'footer.inc.php'); ?>