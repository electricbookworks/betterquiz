<?php
/**
 * @file Run database migrations
 */
include_once("include.php");
$db = Database::Get();

try {
$db->query(<<<EOSQL
	alter table user
		add is_admin tinyint(1) default 0
EOSQL
);
$db->query(<<<EOSQL
	alter table user
		add reset_request_code varchar(40) null
EOSQL
);

$db->query(<<<EOSQL
	alter table user
		add reset_request_valid datetime null
EOSQL
);
} catch (Exception $e) {}

$db->query(<<<EOSQL
	update user set is_admin=1 
	where email in (select email from admins)
EOSQL
	);
$db->query(<<<EOSQL
	update user set is_admin=1 
	where email='craig@lateral.co.za';
EOSQL
	);
echo "database migrated to admin_passwords";