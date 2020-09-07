<?php

namespace App;

use User;

abstract class Model
{
    public static function find(int $id)
    {
        $table = strtolower(static::class);
        $sql = 'SELECT * FROM '. $table .' WHERE id = :id';
        $pdo = \getPdo();
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
    public function delete(int $id)
    {
        $table = strtolower(static::class);
        $sql = 'DELETE FROM '. $table .' WHERE id = :id';
        $pdo = \getPdo();
        var_dump($sql);
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
    public function save(int $id = NULL,string $name,string $last_name,string $created_at,string $updated_at = NULL)
    {
        $table = new static();
        $parameters = get_object_vars($table);
        $count = func_num_args();
        for($i=0;$i<$count;$i++){
            $arguments[$i] = func_get_arg($i);
        }
        $fields = array();
        $i = 0;
//        var_dump($parameters);
        foreach ($parameters as $key => $item){
            $fields[$i] = $key;
            $i++;
        }




//      update
        if($id != NULL){
            $table = \strtolower(static::class);
            $len= count($fields);

            $sql = 'UPDATE '. $table . ' SET';
            for($i = 0;$i < $len;$i++){
                if($i == 0) {
                    $sql .=  ' '.$fields[$i].' = :' . $fields[$i];
                }else{
                    $sql .=  ', '.$fields[$i].' = :' . $fields[$i];
                }
            }
            $sql .=' WHERE id = :id';
            var_dump($sql);
            echo '<br>';
            $pdo = \getPdo();
            $stmt = $pdo->prepare($sql);
            for ($i=0;$i<$count;$i++){
                $stmt->bindValue(':'.$fields[$i], $arguments[$i]);
            }
            $stmt->execute();
        }


//      create
        else {
            $table = \strtolower(static::class);
            $sql = 'INSERT INTO '. $table .' ('.\implode(", ",$fields).') VALUES (:'.\implode(', :',$fields).')';
            $pdo = \getPdo();
            var_dump($sql);
            $stmt = $pdo->prepare($sql);
            for ($i=0;$i<$count;$i++){
                $stmt->bindValue(':'.$fields[$i], $arguments[$i]);
            }
            $stmt->execute();
        }
    }
}