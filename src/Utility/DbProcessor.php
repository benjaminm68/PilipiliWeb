<?php

namespace App\Utility;

use Symfony\Component\HttpFoundation\RequestStack;

class DbProcessor
{

    private $request;

    public function __construct(RequestStack $request)
    {
        $this->request = $request->getCurrentRequest();
    }

    public function __invoke(array $record)
    {

        $record['extra']['clientIp'] = $this->request->getClientIp();
        $record['extra']['url'] = $this->request->getBaseUrl();
        dd($record);
        return $record;
    }
}