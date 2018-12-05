<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('get_test', function () {
    return 'this is get test';
});

$router->post('post_test', function () {
    return 'this is post';
});

$router->get('user', function () {
	$name = $_GET['name'];
    return $name;
});

$router->post('nodeApply', 'NodeApplyController@nodeApply');

$router->post('registered','AccountController@registered');
$router->post('resetSecret','AccountController@resetSecret');
$router->post('resetTelNumber','AccountController@resetTelNumber');
$router->post('loading','AccountController@loading');
$router->get('getSmsMsg','AccountController@getSmsMsg');
$router->get('userAmount','AccountController@userAmount');
$router->get('userRecord','AccountController@userRecord');

$router->get('nodeList','NodeDataController@nodeList');
$router->get('nodeBeforeList','NodeDataController@nodeBeforeList');
$router->get('nodeData','NodeDataController@nodeData');
$router->get('getNodeNameByCity','NodeDataController@getNodeNameByCity');
$router->get('getVoteAccount','NodeDataController@getVoteAccount');

$router->post('voteConfirm','VoteController@voteConfirm');
$router->post('cancelVote','VoteController@cancelVote');
$router->get('userVoteList','VoteController@userVoteList');
$router->get('totalVote','VoteController@totalVote');

$router->get('getEthAddress','WalletController@getEthAddress');
$router->post('withdrawCoin','WalletController@withdrawCoin');
$router->get('getFee','WalletController@getFee');

$router->get('bonusExpected','BonusController@bonusExpected');
$router->post('bonus','BonusController@bonus');
$router->get('getRate','BonusController@getRate');
$router->get('totalBonus','BonusController@totalBonus');






















