<?php
/**
 * Created by PhpStorm.
 * User: legovaer
 * Date: 1/05/14
 * Time: 12:32
 */

class updateJUnitName extends Task {
    private $fileName = null;
    private $newName = null;

    function setFileName($fileName) {
        $this->fileName = $fileName;
    }

    function setNewName($newName) {
        $this->newName = $newName;
    }

    function main() {
        // Load XML file
        $xml = simplexml_load_file($this->fileName);

        // Specify the new name of the testcase. First load the old one and then add the browser/OS information.
        $oldName = $xml->testcase['name'];
        $newName = $this->newName;

        // Write the updated name to the file
        $xml->testcase['name'] = $oldName . "[ ".$newName." ]";
        $xml->asXML($this->fileName);
    }
} 