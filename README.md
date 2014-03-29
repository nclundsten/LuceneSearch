ZF2 Lucene Search Module
Nigel Lundsten
Mar 28 2014

NOTE: 
- zendsearch is not currently in packagist
- composer wont resolve nested dependencies that are not on packagist

you need to add these lines to your composer.json in the root of your project

"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/zendframework/ZendSearch"
    }
],
"require": {
    "zendframework/zendsearch"    : "dev-master"
}
