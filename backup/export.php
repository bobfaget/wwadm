<?php 
    //ENTER THE RELEVANT INFO BELOW
    $mysqlUserName      = "u868558704_ww";
    $mysqlPassword      = "y8vztaqu";
    $mysqlHostName      = "mysql.hostinger.in";
    $DbName             = "u868558704_ww";
    $backup_name        = "mybackup.sql";
    $tables             = array("episodes", "links", "movies", "reported", "requests", "tvshows", "users");

   //or add 5th parameter(array) of specific tables:    array("mytable1","mytable2","mytable3") for multiple tables

    Export_Database($mysqlHostName,$mysqlUserName,$mysqlPassword,$DbName,  $tables=false, $backup_name=false );

    function Export_Database($host,$user,$pass,$name,  $tables=false, $backup_name=false )
    {
        $mysqli = new mysqli($host,$user,$pass,$name); 
        $mysqli->select_db($name); 
        $mysqli->query("SET NAMES 'utf8'");

        $queryTables    = $mysqli->query('SHOW TABLES'); 
        while($row = $queryTables->fetch_row()) 
        { 
            $target_tables[] = $row[0]; 
        }   
        if($tables !== false) 
        { 
            $target_tables = array_intersect( $target_tables, $tables); 
        }
        foreach($target_tables as $table)
        {
            $result         =   $mysqli->query('SELECT * FROM '.$table);  
            $fields_amount  =   $result->field_count;  
            $rows_num=$mysqli->affected_rows;     
            $res            =   $mysqli->query('SHOW CREATE TABLE '.$table); 
            $TableMLine     =   $res->fetch_row();
            $content        = (!isset($content) ?  '' : $content) . "\n\n".$TableMLine[1].";\n\n";

            for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0) 
            {
                while($row = $result->fetch_row())  
                { //when started (and every after 100 command cycle):
                    if ($st_counter%100 == 0 || $st_counter == 0 )  
                    {
                            $content .= "\nINSERT INTO ".$table." VALUES";
                    }
                    $content .= "\n(";
                    for($j=0; $j<$fields_amount; $j++)  
                    { 
                        $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) ); 
                        if (isset($row[$j]))
                        {
                            $content .= '"'.$row[$j].'"' ; 
                        }
                        else 
                        {   
                            $content .= '""';
                        }     
                        if ($j<($fields_amount-1))
                        {
                                $content.= ',';
                        }      
                    }
                    $content .=")";
                    //every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
                    if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) 
                    {   
                        $content .= ";";
                    } 
                    else 
                    {
                        $content .= ",";
                    } 
                    $st_counter=$st_counter+1;
                }
            } $content .="\n\n\n";
        }
        //$backup_name = $backup_name ? $backup_name : $name."___(".date('H-i-s')."_".date('d-m-Y').")__rand".rand(1,11111111).".sql";
        $backup_name = $backup_name ? $backup_name : $name."_".date("Y-m-d").".sql";
        //header('Content-Type: application/octet-stream');   
        //header("Content-Transfer-Encoding: Binary"); 
        //header("Content-disposition: attachment; filename=\"".$backup_name."\"");  
        //echo $content; exit;
$api_dev_key = 'c38ec99f32ced18f44d5587bba190128'; // your api_developer_key 
$api_paste_code = "/* DB Backup ".date("Y-m-d h:i:s")." - This Data Was Automatically Generated */ \n".$content; // your paste text, Add NEW LINES FOR New Lines, It will be replaced!!! 
$api_paste_private = '1'; // 0=public 1=unlisted 2=private 
$api_paste_name = $name.'_'.date("Y-m-d h.i.s").".sql"; // name or title of your paste 
$api_paste_expire_date = 'N'; 
$api_paste_format = 'text'; 
$api_user_key = 'c6c1b832aa50ae41590872bed055ab98'; // if an invalid api_user_key or no key is used, the paste will be create as a guest 
$api_paste_name = urlencode($api_paste_name); 
$api_paste_code = urlencode($api_paste_code); 
$api_paste_code = str_replace("%5Cn", "%0A", $api_paste_code);
$url = 'http://pastebin.com/api/api_post.php'; 
$ch = curl_init($url); curl_setopt($ch, CURLOPT_POST, true); 
curl_setopt($ch, CURLOPT_POSTFIELDS, 'api_option=paste&api_user_key='.$api_user_key.'&api_paste_private='.$api_paste_private.'&api_paste_name='.$api_paste_name.'&api_paste_expire_date='.$api_paste_expire_date.'&api_paste_format='.$api_paste_format.'&api_dev_key='.$api_dev_key.'&api_paste_code='.$api_paste_code.''); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_VERBOSE, 1); 
curl_setopt($ch, CURLOPT_NOBODY, 0); $response = curl_exec($ch);
echo $response;
    }
?>
