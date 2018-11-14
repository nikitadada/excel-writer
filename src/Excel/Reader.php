<?php

namespace App\Excel;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

final class Reader
{
    /** @var $reader  Xlsx */
    private $reader;

    private $spreadsheet;

    public function __construct()
    {
        $this->reader = IOFactory::createReader('Xlsx');
        $this->reader->setReadDataOnly(true);
    }

    public function getSpreadsheet($inputFileName)
    {
        $this->spreadsheet = $this->reader->load($inputFileName);
        return $this->spreadsheet;
    }


}