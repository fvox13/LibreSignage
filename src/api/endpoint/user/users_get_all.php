<?php
/*
*  ====>
*
*  *Get a list of all existing users along with the
*  available userdata.*
*
*  Return value
*    * users = A dictionary of the users and their data
*      with the usernames as the keys.
*
*      * user     = The name of the user.
*      * groups   = The groups the user is in.
*
*    * error      = An error code or API_E_OK on success.
*
*  <====
*/

require_once($_SERVER['DOCUMENT_ROOT'].'/common/php/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/api/api.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/api/api_error.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/common/php/auth/auth.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/common/php/auth/user.php');

$USERS_GET_ALL = new APIEndpoint(array(
	APIEndpoint::METHOD		=> API_METHOD['GET'],
	APIEndpoint::RESPONSE_TYPE	=> API_RESPONSE['JSON']
));
session_start();
api_endpoint_init($USERS_GET_ALL, auth_session_user());

if (!auth_is_authorized(array('admin'), NULL, FALSE)) {
	throw new APIException(
		API_E_NOT_AUTHORIZED,
		"Not authorized."
	);
}

$users = user_array();
$ret_data = array(
	'users' => array()
);

foreach ($users as $u) {
	$ret_data['users'][$u->get_name()] = array(
		'user' => $u->get_name(),
		'groups' => $u->get_groups()
	);
}

$USERS_GET_ALL->resp_set($ret_data);
$USERS_GET_ALL->send();
