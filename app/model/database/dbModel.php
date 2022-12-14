<?php

namespace App\model\database;

use Core\Application;
use Core\Model;

abstract class dbModel extends Model
{

    abstract public static function getTableShort():string;
    abstract public static function tableName(): string;
    abstract public static function PrimaryKey(): string;
    abstract public function attributes(): array;
//    abstract public function relations(): array;
    private string $WherePrimaryKey='';

//    abstract public function getPrimaryKey(): string;
    /**
     * @param string $id
     */

    /**
     * @return string
     */
    public function getWherePrimaryKey(): string
    {
        return $this->WherePrimaryKey;
    }

    /**
     * @param string $PrimaryKey
     */
    public function setWherePrimaryKey(string $PrimaryKey): void
    {
        $this->WherePrimaryKey = $PrimaryKey;
    }

    public static function RetrieveAll(): bool|array
    {
        $tableName = static::tableName();
        $statement = self::prepare("SELECT * FROM $tableName");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS,static::class);
    }



    private function saveRealtion(string $table1,string $table2){
        return $table1.'_'.$table2;
    }
    private function countDigits($MyNum){
        $MyNum = (int)$MyNum;
        $count = 0;

        while($MyNum != 0){
            $MyNum = (int)($MyNum / 10);
            $count++;
        }
        return $count;
    }


    private function getPrimaryKey($table){
        $sql = "SHOW INDEX FROM $table WHERE Key_name = 'PRIMARY'";
        $gp = self::prepare($sql);
        $gp->execute();
        $cgp = $gp->rowCount();
        $PK=[];
        if ($cgp > 0) {
            // Note I'm not using a while loop because I never use more than one prim key column
            $result = $gp->fetchAll();
            foreach ($result as $key => $value) {
                $PK[] = $value['Column_name'];
            }
            return($PK);
        } else {
            return(false);
        }
    }

    public function getIndex($number)
    {
        if($this->countDigits($number)==1){
            return '00'.$number;
        }
        elseif ($this->countDigits($number)==2){
            return '0'.$number;
        }
        else{
            return $number;
        }
    }

    public function save()
    {
        $tableName = static::tableName();
        $attributes=$this->attributes();
        $PK=$this->getPrimaryKey(static::tableName())[0];
        $params=array_map(fn($attr)=>":$attr",$attributes);
        $statement=self::prepare("INSERT INTO $tableName (".implode(',',$attributes).") VALUES (".implode(',',$params).")");
//        $attributes['username']="username";
        foreach ($attributes as $attribute)
        {
            $statement->bindValue(":$attribute",$this->{$attribute});
        }


        $statement->execute();


        return true;
    }


    public function update($id): bool
    {
        $tableName = static::tableName();
        $attributes=$this->attributes();
        $params=array_map(fn($attr)=>":$attr",$attributes);

        $demo='UPDATE '.$tableName.' SET ';
        foreach ($attributes as $attribute)
        {
            if ($attribute==static::PrimaryKey()){
                continue;
            }
            $demo.=$attribute.'="'.$this->{$attribute}.'", ';
        }
        $demo=substr($demo,0,-2);
        $demo.=' WHERE '.static::PrimaryKey().'="'.$id.'"';
        $statement=self::prepare($demo);
        $statement->execute();
        return true;
    }
    public static function findOne($where)
    {
        $tableName= static::tableName();
        $attributes=array_keys($where);

        $sql=implode(" AND ",array_map(fn($attr)=>"$attr=:$attr",$attributes));

        $statement=self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach ($where as $key=>$item)
        {
            $statement->bindValue(":$key",$item);
        }
        $statement->execute();

        return $statement->fetchObject(static::class);
    }
    public static function DeleteOne($where)
    {
        $tableName= static::tableName();
        $attributes=array_keys($where);
        $sql=implode("AND",array_map(fn($attr)=>"$attr=:$attr",$attributes));
        $statement=self::prepare("DELETE FROM $tableName WHERE $sql");
        foreach ($where as $key=>$item)
        {
            $statement->bindValue(":$key",$item);
        }
        return $statement->execute();

    }
    public static function prepare($sql): bool|\PDOStatement
    {
        return Application::$app->db->pdo->prepare($sql);
    }

    private function getColoumnName(string $table)
    {
        $sql="SHOW columns FROM ".$table;
        $statement=self::prepare($sql);
        $statement->execute();
        $result=$statement->fetchAll();
        $columns=[];
        foreach ($result as $item)
        {
            $columns[]=$item['Field'];
        }
        return $columns;
    }


}