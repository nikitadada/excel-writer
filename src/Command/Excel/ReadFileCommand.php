<?php

namespace App\Command\Excel;

use App\Command\BaseCommand;
use App\Document\Data;
use App\Excel\Reader;
use Doctrine\ODM\MongoDB\DocumentManager;
use MongoDate;
use MongoUpdateBatch;
use Symfony\Component\Console\Input\{
    InputArgument, InputInterface
};
use Symfony\Component\Console\Output\OutputInterface;


class ReadFileCommand extends BaseCommand
{
    private $reader;
    private $dm;

    public function __construct(Reader $reader, DocumentManager $dm)
    {
        parent::__construct();

        $this->dm = $dm;
        $this->reader = $reader;
    }

    protected function configure()
    {
        $this
            ->addArgument('file-name', InputArgument::REQUIRED, 'File name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $collection = $this->dm->getDocumentCollection(Data::class);

        $fileName = $input->getArgument('file-name');
        $rowGenerator = $this->reader->getData($fileName);

        $data = [];
        foreach ($rowGenerator as $key => $value) {
            $data[$key] = $value;
        }

        if (!$data) {
            return;
        }

        $batch = new MongoUpdateBatch($collection->getMongoCollection());

        foreach ($data as $id => $row) {
            $row['date'] = new MongoDate($row['date']->getTimestamp());
            $row['fileName'] = $fileName;

            $batch->add([
                'q' => [
                    '_id' => $id,
                ],
                'u' => [
                    '$set' => $row,
                ],
                'upsert' => true,
            ]);
        }

        $batch->execute();

        $total = $this->aggregateData($fileName);

        foreach ($total as $t) {
            $date = $t['_id']['date'];
            $value = $t['value'];
            $fee = $t['fee'];
            $output->writeln("<info>$date: fee = $fee, value = $value</info>");
        }

    }

    private function aggregateData($fileName): array
    {
        $qb = $this->dm->createQueryBuilder(Data::class);

        $qb->field('fileName')->equals($fileName);

        $match = $qb->getQueryArray();

        $data =
            $this->dm->getDocumentCollection(Data::class)
                ->aggregate([
                    ['$match' => $match],
                    ['$group' => [
                        '_id' => [
                            'date' => ['$dateToString' => ['format' => '%Y/%m/%d', 'date' => '$date']],
                        ],
                        'value' => ['$sum' => '$value'],
                        'fee' => ['$sum' => '$fee']
                    ]]
                ])
                ->toArray();

        return $data;

    }

}