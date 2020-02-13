<?php

#- default include
include(__DIR__.'/../inc/default.inc.php');

#- Html header
$title = 'DB difference list' ;
html_header($title);

//チェックTables
include(__DIR__.'/../check_tables.inc.php');

#- Controller =======================================================

//チェックするテーブルが選択されているかチェック
if (empty($_COOKIE['choice_table']) || count($_COOKIE['choice_table']) < 1) {
	//リダイレクト
	header('Location: '.HOME_URL);
	exit;
}

//プライマリーキー取得
if (isset($_GET['set']) || isset($_GET['view'])) {
	$sql = "
		SELECT TABLE_NAME, COLUMN_NAME
		FROM information_schema.COLUMNS c
		WHERE TABLE_SCHEMA='".DATABASE."'
		AND TABLE_NAME IN ('".implode("','", $_COOKIE['choice_table'])."')
		AND (COLUMN_KEY='PRI' OR COLUMN_NAME LIKE 'created_at' OR COLUMN_NAME LIKE 'updated_at' OR COLUMN_NAME LIKE 'last_update')
	";
	$res = $db->run($sql);
	$table_info = array();
	foreach ($res as $v) {
		$table_name = $v['TABLE_NAME'];
		$column_name = $v['COLUMN_NAME'];
		if ($column_name == 'created_at') {
			$table_info[$table_name]['created_at'] = true;
		} else if ($column_name == 'updated_at') {
			$table_info[$table_name]['updated_at'] = true;
		} else if ($column_name == 'last_update') {
			$table_info[$table_name]['last_update'] = true;
		} else {
			$table_info[$table_name]['pri'] = $column_name;
		}
	}
}

//クッキー削除
if (isset($_GET['section'])) {
	clear_cookie();
	//リダイレクト
	header('Location: '.HOME_URL);
	exit;
}

//チェック開始(最後のデータ保存)
if (!empty($_GET['set'])) {
	//既存のクッキー削除
	clear_cookie();
	//時間保存
	setcookie('check_time', date('Y-m-d H:i:s'));
	foreach ($table_info as $table_name => $v) {
		$pri = $v['pri'];
		$sql = "SELECT $pri FROM $table_name WHERE 1 ORDER BY $pri DESC LIMIT 1";
		$set->list[$table_name] = $res = $db->run($sql);
		if (isset($res[0][$pri])) {
			setcookie($table_name, $res[0][$pri]);
		}
	}
	//リダイレクト
	header('Location: '.HOME_URL.'check');
	exit;
}

//差分リスト表示
if (!empty($_GET['view'])) {
	//クッキーチェック
	if (empty($_COOKIE['check_time'])) {
		//リダイレクト
		header('Location: '.HOME_URL);
		exit;
	}
	foreach ($table_info as $table_name => $v) {
		$pri = $v['pri'];
		//データがなくて基準値が保存されてないとき
		$cookie_value = 0;
		if (!empty($_COOKIE[$table_name])) {
			$cookie_value = $_COOKIE[$table_name];
		}
		//基準値移行を取得
		$sql = "SELECT * FROM $table_name WHERE $pri > ".$cookie_value;
		if ($_GET['view'] == 1) {
			if (isset($v['created_at'])) {
				$sql .= " OR created_at > '".$_COOKIE['check_time']."'";
			}
			if (isset($v['updated_at'])) {
				$sql .= " OR updated_at > '".$_COOKIE['check_time']."'";
			}
			if (isset($v['last_update'])) {
				$sql .= " OR last_update > '".$_COOKIE['check_time']."'";
			}
		}
		$sql .= " ORDER BY $pri DESC";
		$set->list[$table_name] = $res = $db->run($sql);
	}
}

#- View =============================================================
view('index', $set);



#- Function =========================================================
//クッキー削除
function clear_cookie()
{
	foreach ($_COOKIE as $k => $v) {
		setcookie($k, null, time() - 600);
	}
}
