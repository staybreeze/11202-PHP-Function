<?php
    $dsn = "mysql:host=localhost;charset=utf8;dbname=school";
    $pdo = new PDO($dsn, 'root', '');
//$rows=all('students',['dept'=>'3']);
//$row=find('students',10);
//$row=find('students',['dept'=>'1','graduate_at'=>'23']);
//$rows=all('students',['dept'=>'1','graduate_at'=>'23']);
//echo "<h3>相同條件使用find()</h3>";
//dd($row);
//echo "<hr>";;
//echo "<h3>相同條件使用all()</h3>";
//dd($rows);

// $up = update("students", '3', ['dept' => '16', 'name' => '張明珠']);

// dd($up);
// $row = del('students', 2);
insert('dept',['code'=>'170','name'=>'戲劇系']);

// -----PDO自訂函式-----

function pdo($db){
    $dsn="mysql:host=localhost;charset=utf8;dbname=$db";
    $pdo=new PDO($dsn,'root','');

    return $pdo;
}

// -----all-----

// SELECT `col1`,`col2`,... FROM `table1`,`table2`,...　WHERE ...
function all($table = null, $where = '', $other = '')
{
    // 如果重複的資料很多，就用include
    // include "./include/connect.php";

    $sql = "select * from `$table` ";
    // 資料不多可以設成自訂函式
    // $pdo=pdo('school');

    // 也可以在全域設定並呼叫
    global $pdo;
    if (isset($table) && !empty($table)) {

        if (is_array($where)) {
            /**
             * ['dept'=>'2','graduate_at'=>12] =>  where `dept`='2' && `graduate_at`='12'
             * $sql="select * from `$table` where `dept`='2' && `graduate_at`='12'"
             */
            if (!empty($where)) {
                foreach ($where as $col => $value) {
                    // 暫時存儲迴圈中生成的條件片段
                    $tmp[] = "`$col`='$value'";
                }
                $sql .= " where " . join(" && ", $tmp);
            }
        } else {
            $sql .= " $where";
        }

        $sql .= $other;
        echo 'all=>' . $sql;
        $rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    } else {
        echo "錯誤:沒有指定的資料表名稱";
    }
}

// -----find-----

function find($table, $id)
{
    $dsn = "mysql:host=localhost;charset=utf8;dbname=school";
    $pdo = new PDO($dsn, 'root', '');
    $sql = "select * from `$table` ";

    if (is_array($id)) {
        foreach ($id as $col => $value) {
            $tmp[] = "`$col`='$value'";
        }
        $sql .= " where " . join(" && ", $tmp);
    } else if (is_numeric($id)) {
        $sql .= " where `id`='$id'";
    } else {
        echo "錯誤:參數的資料型態比須是數字或陣列";
    }
    echo 'find=>' . $sql;
    $row = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    return $row;
}

// -----update-----

// UPDATE `table` SET `col1`='value1',`col2`='value2',...　WHERE ...

function update($table, $id, $cols)
{
    $dsn = "mysql:host=localhost;charset=utf8;dbname=school";
    $pdo = new PDO($dsn, 'root', '');

    $sql = "update `$table` set ";
    // 因為要填入兩個變數，因此要分別判斷兩個變數
    // 判斷$cols
    if (!empty($cols)) {
        foreach ($cols as $col => $value) {
            $tmp[] = "`$col`='$value'";
        }
    } else {
        echo "錯誤:缺少要編輯的欄位陣列";
    }

    $sql .= join(",", $tmp);

    // 判斷$id

    // 當$id只要查id的時候
    // if (is_array($id)) {
    //     foreach ($id as $col => $value) {
    //         $tmp[] = "`$col`='$value'";
    //     }
    // } elseif (is_numeric($id)) {
    //     $sql .= " where `id`='$id'";
    // } else {
    //     echo "錯誤:參數的資料型態應為數字或陣列";
    // }


    // $id可以不只查id，也可以查其他的where
    if (is_array($id)) {
        foreach ($id as $col => $value) {
            $tmp[] = "`$col`='$value'";
        }
        $sql .= " where " . join(" && ", $tmp);
    } else if (is_numeric($id)) {
        $sql .= " where `id`='$id'";
    } else {
        echo "錯誤:參數的資料型態比須是數字或陣列";
    }
    echo $sql;
    return $pdo->exec($sql);

}

// -----delete-----

// DELETE FROM `table` WHERE ...

function del($table, $id)
{
    $dsn = "mysql:host=localhost;charset=utf8;dbname=school";
    $pdo = new PDO($dsn, 'root', '');
    $sql = "delete from `$table` ";

    if (is_array($id)) {
        foreach ($id as $col => $value) {
            $tmp[] = "`$col`='$value'";
        }
        $sql .= " where " . join(" && ", $tmp);
    } else if (is_numeric($id)) {
        $sql .= " where `id`='$id'";
    } else {
        echo "錯誤:參數的資料型態比須是數字或陣列";
    }

    echo 'del=>' . $sql;
    $row = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    return $row;
}

// -----insert-----


// INSERT INTO `table`(`col1`,`col2`,`col3`,`col4`,`col5`) 
//             VALUES('value1','value1','value1','value1','value1','value1');



function insert($table,$values){

    
    global $pdo;

$sql = "insert into `$table` ";

// $cols="(``,``,``,``,)";
// $vals="('','','','',)";

$cols="(`".join("`,`",array_keys($values))."`)";
$vals="('".join("','",$values)."')";

$sql=$sql . $cols  ." values ".$vals;
// $sql=insert into `$table` . (``,``,``,``,) ." values ".('','','','',);

echo $sql;
return $pdo->exec($sql);


}

function dd($array)
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}
