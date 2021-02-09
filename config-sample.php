<?php
namespace config;

class Config {
    // clef API de ipdata.co
    public $apiKey = 'API_KEY';
    // path des logs apache
    public $logPath = '/val/log/apache2';
    // filtre aditionnels sur la zgrep
    public $endGrepCommandLine = '|  grep -v robots.txt | grep -v sitemap | grep -v ico | grep -v .png |  grep -v ads.txt | grep -v AutoDiscover ';
    // mettre true pour sortie JSON
    public $jsonOut = false;
    public $curMonth = 'Oct'; // si null prends le mois en cours, sinon utilise celui la
}

