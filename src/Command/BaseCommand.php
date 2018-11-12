<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;

abstract class BaseCommand extends Command
{
    public function __construct()
    {
        $name = get_class($this);
        $name = preg_replace('/Command$/', '', $name);

        $name = strtolower(preg_replace(array('/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'), array('\\1-\\2', '\\1-\\2'), strtr($name, [__NAMESPACE__ . '\\' => '', '\\' => ':'])));

        parent::__construct($name);
    }


}