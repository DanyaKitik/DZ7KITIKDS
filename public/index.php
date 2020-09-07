<?php


require "../vendor/autoload.php";

$dbh = new \PDO('mysql:host=mysql;dbname=sandbox','root','secret');
$dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
function getPdo()
{
    global $dbh;
    return $dbh;
}
class User extends \App\Model
{
    protected $id;
    protected $name;
    protected $last_name;
    protected $created_at;
    protected $updated_at;
}

try {
/** CREATE */
    User::save(NULL,'Name1','LastName1', '2020-07-10','2020-07-18');
echo '<pre>';
/** READ */
    User::find(1);
echo '</pre>';
/** UPDATE */
    User::save(1,'Name1 Updated',"LastName1 Updated", "2020-07-10","2020-07-18");
/** READ */
echo '<pre>';
    User::find(1);
echo '</pre>';
/** DELETE */
    User::delete(1);
/** READ */
echo '<pre>';
    User::find(1);
echo '</pre>';
}catch (Exception $exception){
    echo $exception;
}
/**
create table user
(
id int unsigned auto_increment,
name varchar(255) not null,
last_name varchar(255) not null,
created_at datetime not null,
updated_at datetime null,
constraint user_pk
primary key (id)
);
 */