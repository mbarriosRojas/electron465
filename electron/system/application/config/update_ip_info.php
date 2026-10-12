<?php


$file_path = "/var/www/cooperativa-electron/system/application/config/ip_local.php";

if(file_exists($file_path)){
    unlink($file_path);
} 


function get_ifconfigline(){
    
       exec("ifconfig", $output);
         
         foreach($output as $line){
                           
                if (preg_match("/(.*)inet addr:(.*)/", $line)){
                    
                    $ip = $line;
                    
                    return $ip;
                    
                }
         }
}


function getLocalIP(){
 
     $ifconfigline = get_ifconfigline();
     $array_ip = explode(" ",trim($ifconfigline));
     $format_ip = substr($array_ip[1],5);
     
     return $format_ip;

}


function format_content_txt(){
    
    $ip_address = getLocalIP();
    $content_txt = '$IP_SERVER_DYNAMIC = "http://'.$ip_address.'";'; 
    
    return $content_txt;   
}

   

$file =fopen($file_path,"a") or die("Error al generar archivo"); 
fputs($file,"<?php".PHP_EOL);
fputs($file,format_content_txt().PHP_EOL);
fclose($file);

echo "La IP ha sido actualizada correctamente.";