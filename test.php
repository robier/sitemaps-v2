<?php

use Robier\SiteMaps\Contract;
use Robier\SiteMaps\Generator;
use Robier\SiteMaps\Path;
use Robier\SiteMaps\Processor;
use Robier\SiteMaps\Type;

require 'vendor/autoload.php';

class test implements Contract\DataProvider {

    public function fetch(): Iterator
    {
        for($i = 0; $i <= 100000; $i++){
            yield new \Robier\SiteMaps\Location('http://google.com/' . $i);
        }
    }
}

$pathData = new Path('/tmp/sitemaps', 'http://example.com/');

$generator = new Generator(new Type\XML('Y-m-d', true, 'styleSheet.xsl'), $pathData);
$generator->data('kita', new test());
$generator->data('kita2', new test());
$generator->data('kita3', new test());
$generator->data('kita4', new test());
$generator->processor(new Processor\IndexFile\GroupTest($pathData));
$generator->processor(new Processor\GZip());

//$generator->processor(new Processor\Apply\Multiple(['kita2', 'kita3'], new Processor\IndexFile($pathData, 'test2')));
//$generator->processor(new Processor\Apply\EntryFiles(new Processor\GZip()));
//$generator->processor(new Processor\Group\All(new Processor\IndexFile($pathData, 'kita')));


//$generator->processor(new Processor\Apply\Group('kita', new Processor\BlackHole()));
//$generator->processor(
//    new Processor\Apply\Groups(['kita', 'kita2'], new Processor\IndexFile($pathData))
////    new Processor\Apply(
////        function(Contract\File $file){
////            return !$file->hasSiteMapIndex();
////        },
////
////    )
//);

foreach($generator as $item){
    var_dump($item);
}

