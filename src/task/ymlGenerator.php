<?php
/**
 * Created by PhpStorm.
 * User: legovaer
 * Date: 1/05/14
 * Time: 12:29
 */

class ymlGenerator extends Task {
    private $name = null;
    private $jsonFile = null;
    private $outputDir = null;
    private $uname = null;
    private $apikey = null;
    private $behatymlFile = null;

    function setTestName($name) {
        $this->name = $name;
    }

    function setJsonFile($jsonFile) {
        $this->jsonFile = $jsonFile;
    }

    function setOutputDir($outputDir) {
        $this->outputDir = $outputDir;
    }

    function setUname($uname) {
        $this->uname = $uname;
    }

    function setApikey($apikey) {
        $this->apikey = $apikey;
    }

    function setBehatymlFile($behatymlFile) {
        $this->behatymlFile = $behatymlFile;
    }

    function main() {
        $json = file_get_contents($this->jsonFile);
        $behatyml = file_get_contents($this->behatymlFile);

        if(!is_dir($this->outputDir)) {
            mkdir($this->outputDir, 0777, TRUE);
        }

        $jsonIterator = new RecursiveIteratorIterator(
            new RecursiveArrayIterator(json_decode($json, TRUE)),
            RecursiveIteratorIterator::SELF_FIRST);
        $counter = 1;

        foreach ($jsonIterator as $key => $val) {
            $clOS = str_replace(' ','',$val['os']);
            $clBrowser = str_replace(' ','',$val['browser']);
            $clVersion = str_replace(' ','',$val['browser-version']);

            $htmlOutputPath = "reports/html/".$clOS."-".$clBrowser."-".$clVersion.".html";

            if(is_array($val)) {
                $yaml = array(
                    'sauce' => array(
                        'formatter' => array(
                            name => 'pretty,junit,html',
                            parameters => array(
                                output_path => 'null,reports/xml/junit/'.$clOS.'-'.$clBrowser.'-'.$clVersion.','.$htmlOutputPath
                            )
                        ),
                        'filters' => array(
                            'tags' => '@multibrowsers'
                        ),
                        'context' => array(
                            'parameters' => array(
                                'test-name' => $this->name
                            )
                        ),
                        'extensions' => array(
                            'Behat\MinkExtension\Extension' => array(
                                'default_session' => 'saucelabs',
                                'saucelabs' => array(
                                    'username' => $this->uname,
                                    'access_key' => $this->apikey,
                                    'capabilities' => array(
                                        'platform' => $val['os'],
                                        'version' => $val['browser-version'],
                                        'name' => '['.$this->name.'] '.$val['os'].' '.$val['browser']
                                    )
                                )
                            )
                        )
                    )
                );
                $yaml = yaml_emit($yaml);
                $regex_patterns = array("/---\n/","/\.\.\./","/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/","/|/");
                $regex_replacements = array("","","\n","");
                $yaml = preg_replace($regex_patterns,$regex_replacements,$yaml);
                $fileName = $this->outputDir."/".$clOS."-".$clBrowser."-".$clVersion.".yml";

                file_put_contents($fileName,$behatyml.PHP_EOL);
                $add = trim($yaml);
                file_put_contents($fileName,$add.PHP_EOL, FILE_APPEND);

                $counter++;
            }
        }
    }
} 