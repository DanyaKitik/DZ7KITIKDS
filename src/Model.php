<?php

namespace App;

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
    public static function delete(int $id)
    {
        $table = strtolower(static::class);
        $sql = 'DELETE FROM '. $table .' WHERE id = :id';
        $pdo = \getPdo();
        var_dump($sql);
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
    public static function save(int $id = NULL,string $name,string $last_name,string $created_at,string $updated_at = NULL)
    {
        $table = new static();
        $temp = \get_object_vars($table);
        $fields = array();
        $i = 0;
        foreach ($temp as $key => $item){
            $fields[$i] = $key;
            $i++;
        }
        if($id != NULL){
            $table = \strtolower(static::class);
            $sql = 'UPDATE '. $table . ' SET';
            $len= count($fields);
            for($i = 0;$i < $len;$i++){
                if($i == 0) {
                    $sql .=  ' '.$fields[$i].' = :' . $fields[$i];
                }else{
                    $sql .=  ', '.$fields[$i].' = :' . $fields[$i];
                }
            }
            $sql .=' WHERE id = :id';
            var_dump($sql);
            $pdo = \getPdo();
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id, ':name'=>$name,':last_name'=>$last_name,':created_at'=>$created_at,':updated_at'=>$updated_at]);
        }
        else {
//            array_shift($fields);
            $table = \strtolower(static::class);
            $sql = 'INSERT INTO '. $table .' ('.\implode(", ",$fields).') VALUES (:'.\implode(', :',$fields).')';
            $pdo = \getPdo();
            var_dump($sql);
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id'=>$id ,':name'=>$name,':last_name'=>$last_name,':created_at'=>$created_at,':updated_at'=>$updated_at]);
        }
    }
}