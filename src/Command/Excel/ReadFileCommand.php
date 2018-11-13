<?php

namespace App\Command\Excel;

use App\Command\BaseCommand;
use App\Excel\Reader;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class ReadFileCommand extends BaseCommand
{
    private $reader;

    public function __construct(Reader $reader)
    {
        $this->reader = $reader;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->addArgument('file-name', InputArgument::REQUIRED, 'File name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileName = $input->getArgument('file-name');

        $spreadsheet = $this->reader->getSpreadsheet($fileName);
        $worksheet = $spreadsheet->getActiveSheet();

        foreach ($worksheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE);
            foreach ($cellIterator as $cell) {
                var_dump($cell->getValue());

            }
        }

        $fileName = $input->getArgument('file-name');
        $output->writeln("<info>file name: $fileName</info>");
    }


}