<?php
require 'flight/Flight.php';

Flight::set('flight.base_url', 'http://localhost:8081/mysoi/mysoi/');
Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=mysoi','root',''));

Flight::route('/', function(){
	$db = Flight::db();
	$st = $db->prepare("select * from d_spending order by created_date asc");
	$st->execute();
	$listSpending = $st->fetchAll(PDO::FETCH_ASSOC);
	// var_dump($listSpending);die();
	Flight::render('index', array('data' => $listSpending));
});

Flight::route('/save_spending', function(){
	// var_dump($_POST);die();
	$db = Flight::db();
	$st = $db->prepare("insert into d_spending (title, nominal, description, created_date) values ('".$_POST['title']."', '".$_POST['nominal']."', '".$_POST['description']."','".date('Y-m-d H:i:s')."')");
	$res = $st->execute();
	if($res){
		Flight::redirect('../');
	} else {
		echo '<script>alert("Nothing to save");</script>';
		Flight::redirect('../');
	}
});

Flight::route('/delete/@id', function($id){
	$db = Flight::db();
	$st = $db->prepare("delete from d_spending where id = ".$id);
	$res = $st->execute();
	if($res){
		Flight::redirect('../');
	} else {
		echo '<pre>error deleting!</pre>';
	}
});

Flight::route('/add', function(){
	Flight::render('form_spending', array());
});

Flight::route('/login', function(){
	Flight::render('login', array());
});

Flight::map('notFound', function(){
	echo '<pre>no route!</pre>';
});

Flight::start();
?>
