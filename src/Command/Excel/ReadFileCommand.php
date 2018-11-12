<?php

namespace App\Command\Excel;

use App\Command\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class ReadFileCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->addArgument('file-name', InputArgument::REQUIRED, 'File name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileName = $input->getArgument('file-name');
        $output->writeln("<info>file name: $fileName</info>");
    }


}