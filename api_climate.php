<?php
/**
 * Created by Ryan Saunders.
 * User: SYSTEM
 * Date: 20/08/2019
 * Time: 12:00 PM
 */


if(!isset($api))
{
    die("Direct call to api_PHP.php is not allowed!");
}

//$data = array();

$dbClimate ="/etc/pihole/climate.db";

$setupVars = parse_ini_file("/etc/pihole/setupVars.conf");


if (isset($_GET['getClimateData24hrs'])  && $auth)
{
    $data = array_merge($data,  getClimateData24hrs($dbClimate));
}

if (isset($_GET['getLastClimateResult'])  && $auth)
{
    $data = array_merge($data,  getLastClimateResult($dbClimate));
}

if (isset($_GET['getAllClimateData'])  && $auth)
{
    $data = array_merge($data,  getAllClimateData($dbClimate));
}


function getAllClimateData($dbClimate)
{
    $data = getClimateData($dbClimate,-1);
    if($data['errr'])
        return [];
    $newarr = array();
    foreach ($data as  $array) {
        array_push($newarr,array_values($array));
    }
    return  array('data' => $newarr );
}

function getLastClimateResult($dbClimate){
    if(!file_exists($dbClimate)){
        // create db if not exists
        exec('sudo pihole -a -cn');
        return array();
    }

    $db = new SQLite3($dbClimate);
    if(!$db) {
        return array("error"=>"Unable to open DB");
    } else {
        // return array("status"=>"success");
    }

    $curdate = date('Y-m-d H:i:s');
    $date = new DateTime();
    $date->modify('-'.$durationdays.' day');
    $start_date =$date->format('Y-m-d H:i:s');

    $sql ="SELECT * from climate order by id DESC limit 1";

    $dbResults = $db->query($sql);

    $dataFromClimateDB= array();


    if(!empty($dbResults)){
        while($row = $dbResults->fetchArray(SQLITE3_ASSOC) ) {
            array_push($dataFromClimateDB, $row);
        }
        return($dataFromClimateDB);
    }
    else{
        return array("error"=>"No Results");
    }
    $db->close();
}

function getClimateData($dbClimate,$durationdays="1")
{
    if(!file_exists($dbClimate)){
        // create db if not exists
        exec('sudo pihole -a -cn');
        return array();
    }
    $db = new SQLite3($dbClimate);
    if(!$db) {
        return array("error"=>"Unable to open DB");
    } else {
        // return array("status"=>"success");
    }

    $curdate = date('Y-m-d H:i:s');
    $date = new DateTime();
    $date->modify('-'.$durationdays.' day');
    $start_date =$date->format('Y-m-d H:i:s');

    if($durationdays == -1)
    {
        $sql ="SELECT * from climate order by id asc";
    }
    else{
        $sql ="SELECT * from climate where timestamp between '${start_date}' and  '${curdate}'  order by id asc;";
    }

    $dbResults = $db->query($sql);

    $dataFromClimateDB= array();


    if(!empty($dbResults)){
        while($row = $dbResults->fetchArray(SQLITE3_ASSOC) ) {
            array_push($dataFromClimateDB, $row);
        }
        return($dataFromClimateDB);
    }
    else{
        return array("error"=>"No Results");
    }
    $db->close();
}


function getClimateData24hrs($dbClimate){
    global $log, $setupVars;
    if(isset($setupVars["CLIMATE_CHART_DAYS"]))
    {
        $dataFromClimateDB = getClimateData($dbClimate,$setupVars["CLIMATE_CHART_DAYS"]);
    }
    else{
        $dataFromClimateDB = getClimateData($dbClimate);
    }


    return $dataFromClimateDB;
}