<?php


require "../vendor/autoload.php";

$dbh = new \PDO('mysql:host=mysql;dbname=sandbox;','root','secret');
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
function getPdo()
{
    global $dbh;
    return $dbh;
}
abstract class Table
{
    protected int $id;

    public static function find(int $id)
    {
        $table = strtolower(static::class);
        $sql = 'SELECT * FROM '. $table .' WHERE id = :id';
        $pdo = getPdo();
        var_dump($sql);
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $userData = $stmt->fetch(\PDO::FETCH_ASSOC);

        if($userData){
            $user = new static();
            foreach ($userData as $name => $value){
                $user->$name = $value;
            }
            var_dump($user);
        }
        else{
            throw new \Exception('there is nothing at this id');
        }
    }
    public static function delete(int $id)
    {
        $table = strtolower(static::class);
        $sql = 'DELETE FROM '. $table .' WHERE id = :id';
        $pdo = getPdo();
        var_dump($sql);
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
    public static function save(int $id = NULL,string $name,string $last_name,string $created_at,string $updated_at = NULL)
    {
        if($id != NULL){
            $table = strtolower(static::class);
            $sql = 'UPDATE '. $table .
                ' SET name = '.'\''.$name.'\''.
                ' , last_name = '.'\''.$last_name.'\''.
                ' , created_at = '.'\''.$created_at.'\''.
                ' , updated_at = '.'\''.$updated_at.'\''.
                ' WHERE id = :id';
            $pdo = getPdo();
            var_dump($sql);
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
        }
        else {
            $table = strtolower(static::class);
            $sql = 'INSERT INTO '. $table .' (name,last_name,created_at,updated_at) VALUES ('.'\''.$name.'\''.','.'\''.$last_name.'\''.','.'\''.$created_at.'\''.','.'\''.$updated_at.'\''.')';
            $pdo = getPdo();
            var_dump($sql);
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
        }
    }
}
class User extends Table
{
    protected int $id;
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