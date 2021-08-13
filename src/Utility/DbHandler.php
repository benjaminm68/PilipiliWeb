<?php

namespace App\Utility;

use App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Handler\AbstractProcessingHandler;

class DbHandler extends AbstractProcessingHandler
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function write(array $record): void
    {
        //Envoi de log en bdd
        $log = new Log();

        $log->setContext($record['context']);
        $log->setLevel($record['level']);
        $log->setLevelName($record['level_name']);
        $log->setMessage($record['message']);

        $this->em->persist($log);
        $this->em->flush();
    }
}