<?php
$config_path = "../IMPORTANT/config.json";
$config = json_decode(file_get_contents($config_path, "r"));

include("../secure/sql_connection.php");

$files_to_copy = [];

function copy_dir($source_path, $suffixe, $goal_path){
    global $files_to_copy;
    $prefixe_size = strlen($source_path);

    foreach(glob($source_path.$suffixe) as $file) {
        $filename = substr($file, $prefixe_size);
        if ($filename != ""){
            copy($file, $goal_path.$filename);
        }
    }
}

function create_a_dir($path){
    mkdir($path, $mode=0777);
}

copy_dir('../update/downloaded_data/source/api/', '*.*', '../api/');
copy_dir('../update/downloaded_data/source/api/upload/', '*.*', '../api/upload/');
copy_dir('../update/downloaded_data/source/IMPORTANT/lang/', '*.json', '../IMPORTANT/lang/');
copy_dir('../update/downloaded_data/source/script/', '*.js', '../script/');
copy_dir('../update/downloaded_data/source/style/', '*.css', '../style/');
copy_dir('../update/downloaded_data/source/', '*.php', '../');
copy_dir('../update/downloaded_data/source/', '*.txt', '../');
create_a_dir("../script/lib");
create_a_dir("../lib");
copy_dir('../update/downloaded_data/source/script/lib/', '*.*', '../script/lib/');
copy_dir('../update/downloaded_data/source/lib/', '*.*', '../lib/');


// Add a new column in the 'user' table
$sql = "SELECT `totp` FROM `user`; ";
$result = $conn->query($sql);

if (!$result){
    $sql = "ALTER TABLE `user` ADD `totp` TEXT NOT NULL AFTER `blocked`;";
    $result = $conn->query($sql);
    
    if (!$result){
        die('{"success": false, "message" : "An error happend while adding the column totp to the table user"}');
    }
    
    echo '{"success": true, "message": "Update successfully done."}';    
}

?>