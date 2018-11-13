<?php

namespace App\Excel;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

final class Reader
{
    /** @var $reader  Xlsx */
    private $reader;

    private $spreadsheet;

    public function __construct($inputFileName)
    {
        $this->reader = IOFactory::createReader('Xlsx');
        $this->reader->setReadDataOnly(true);
        $this->spreadsheet = $this->reader->load($inputFileName);
    }

    public function getSpreadsheet()
    {
        return $this->spreadsheet;
    }


}