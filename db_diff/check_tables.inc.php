<?php

//Tables to exclude from list display
$ignore = array(
	array('operation' => 'NOT LIKE', 'value' => 'nicer_%'),
	array('operation' => '!=',       'value' => 'rental'),
);

//Work table group
$section = array(
	//All tables
	'all' => array(
		'title' => 'All tables',
		'table_list' => array(
			'actor',
			'actor_info',
			'address',
			'category',
			'city',
			'country',
			'customer',
			'customer_list',
			'film',
			'film_actor',
			'film_category',
			'film_list',
			'film_text',
			'inventory',
			'language',
			'nicer_but_slower_film_list',
			'payment',
			'rental',
			'sales_by_film_category',
			'sales_by_store',
			'staff',
			'staff_list',
			'store',
		)
	),
	//Address related tables
	'group_address' => array(
		'title' => 'Address',
		'table_list' => array(
			'address',
			'city',
			'country',
		)
	),
	//Store related tables
	'group_store' => array(
		'title' => 'Store',
		'table_list' => array(
			'address',
			'category',
			'city',
			'country',
			'customer',
			'payment',
			'sales_by_film_category',
			'sales_by_store',
			'staff',
			'store',
		)
	),
);