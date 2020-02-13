<?php

#- default include
include(__DIR__.'/inc/default.inc.php');

#- Html header
$title = 'Table selection' ;
html_header($title);

//check Tables
include(__DIR__.'/check_tables.inc.php');

#- Controller =======================================================

//Get all tables
$sql = "
	SELECT table_name
	FROM information_schema.TABLES
	WHERE TABLE_SCHEMA='".DATABASE."'
";

//Exclude table
foreach ($ignore as $v) {
	if (!empty($v['operation']) && !empty($v['value'])) {
		$operation = $v['operation'];
		$value = $v['value'];
		$sql .= " AND table_name $operation '$value'";
	}
}
$sql .= " ORDER BY table_name";
$res = $db->run($sql);

$set->list = array('TABLE_NAME');
foreach ($res as $v) {
	$set->list[] = $v['table_name'];
}

//Batch selection
if (isset($_GET['section'])) {
	clear_cookie($set->list);
	foreach ($section as $name => $info) {
		if ($_GET['section'] == $name) {
			$i = 0;
			foreach ($info['table_list'] as $table_name) {
				setcookie("choice_table[$i]", $table_name);
				$i++;
			}
		}
	}
	//Redirect
	header('Location: '.HOME_URL);
	exit;
}

//POST value
if (!empty($_POST['choice_table'])) {
	clear_cookie($set->list);
	$i = 0;
	foreach ($_POST['choice_table'] as $table_name) {
		setcookie("choice_table[$i]", $table_name);
		$i++;
	}
	//Redirect
	header('Location: '.HOME_URL);
	exit;
}

#- View =============================================================

$set->section = $section;
view('choice', $set);



#- Function =========================================================

//cookie clear
function clear_cookie($table_list)
{
	foreach ($_COOKIE['choice_table'] as $i => $tmp) {
		setcookie("choice_table[$i]", null, time() - 600);
	}
}