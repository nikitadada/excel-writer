<?php

namespace App\Excel;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Shared\Date;

final class Reader
{
    /** @var $reader  Xlsx */
    private $reader;

    private $meta;

    public function __construct()
    {
        $this->reader = IOFactory::createReader('Xlsx');
        $this->reader->setReadDataOnly(true);
    }

    public function getData($inputFileName)
    {
        $spreadsheet = $this->reader->load($inputFileName);

        $worksheet = $spreadsheet->getActiveSheet();

        $count = 0;
        foreach ($worksheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            if ($row->getRowIndex() === 1) {
                $this->parseHeader($cellIterator);
            } else {
                yield $count => $this->parseRow($cellIterator);
                $count++;
            }
        }
    }

    private function parseHeader($cellIterator)
    {
        $meta = [];

        foreach ($cellIterator as $cell) {
            $meta[$cell->getColumn()] = $this->normalize($cell->getValue());
        }

        $this->meta = $meta;
    }

    private function normalize($name)
    {
        return str_replace(' ', '', strtolower($name));
    }

    private function parseRow($cellIterator)
    {
        $result = [];
        foreach ($cellIterator as $cell) {
            $column = $this->getColumnName($cell->getColumn());
            if($column === 'date') {
                $result[$column] = Date::excelToDateTimeObject($cell->getValue());
            } else {
                $result[$column] = $cell->getValue();
            }
        }

        return $result;
    }

    private function getColumnName($col)
    {
        if (is_null($this->meta)) {
            throw new \LogicException('Metadata is not defined');
        }

        return $this->meta[$col];
    }
}