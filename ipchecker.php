<?php

require __DIR__ . '/vendor/autoload.php';

use Ipdata\ApiClient\Ipdata;

use Symfony\Component\HttpClient\Psr18Client;
use Nyholm\Psr7\Factory\Psr17Factory;

require 'config.php';
use config\Config;

$httpClient = new Psr18Client();
$psr17Factory = new Psr17Factory();
$config = new config;
$ipdata = new Ipdata($config->apiKey, $httpClient, $psr17Factory);

//$data = $ipdata->lookup('69.78.70.144');
//echo json_encode($data, JSON_PRETTY_PRINT);
$month = !empty($config->curMonth) ? $config->curMonth : date('M');
$year = date('Y');
echo "\n// IPCHECKER APP V0.1\n\n";
echo "// Jour à extraire (mois : $month/$year) ?\n// >>> ";
$day = intval(fgets(STDIN));
$cmd = "/bin/zgrep -i \" 404 \" $config->logPath/* | grep '$day/$month' $config->endGrepCommandLine";
if(!$config->jsonOut) {
    echo $cmd . "\n";
}
$lines = null;
exec($cmd,$lines);
$ipConnues = [];
$ips = [];
foreach ($lines as $line) {
    $cut = explode(':',$line);
    $site = $cut[0];
    $ip = trim(explode('-',$cut[1])[0]);
    if(count(explode(".",$ip)) != 4) {
        continue;
    }
    $date = explode('[',$cut[1])[1];
    $date = str_replace('/','-',$date);
    $url = explode('"',$cut[count($cut) -1])[1];
    $status = explode('"',$cut[count($cut) -1])[2];
    $status = explode(" ",$status)[1];

    if(in_array($ip, $ipConnues)) {
        $ips[$ip]['count'] = $ips[$ip]['count'] +1;
        $ips[$ip]['urls'][] = $url;
        $ips[$ip]['logs'][] = $line;
    }
    if(!in_array($ip, $ipConnues)) {
        $ipConnues[] = $ip;
        $ipInfos = $ipdata->lookup($ip);
        if(empty($ipInfos['asn'])) {
            $ipInfos['asn'] = ['name' => 'Unkn', 'route' => 'Unkn'];
        }
        $ips[$ip] = [
            'site' => $site,
            'ip' => $ip,
            'date' => $date,
            'urls' => [$url],
            'status' => $status,
            'count' => 1,
            'country_code' => $ipInfos['country_code'],
            'country_name' => $ipInfos['country_name'],
            'asn_name' => $ipInfos['asn']['name'],
            'route' => $ipInfos['asn']['route'],
            'emoji_flag' => $ipInfos['emoji_flag'],
            'logs' => [$line]
        ];
    }
}
if($config->jsonOut) {
    echo json_encode($ips,JSON_PRETTY_PRINT);
} else {
    foreach ($ips as $ip) {
        echo  "---------------------\n";
        echo "IP :" . $ip['ip'] . "\n";
        foreach ($ip['logs'] as $log) {
            echo "   " .   $log . "\n";
        }
        echo "# PAYS :" . $ip['country_code'] . " - " . $ip["asn_name"]  . "\n";
        echo $ip['ip'] . "\n";
        echo $ip['route'] . "\n" ;
        echo  "---------------------\n\n";
    }
}

