<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Contracts\Repositories\ActivityLogRepository;

class ActivityLogController extends ApiController
{
    protected $logRepository;

    public function __construct(ActivityLogRepository $logRepository)
    {
        parent::__construct();
        $this->logRepository = $logRepository;
    }

    public function getLogsByGroupId($groupId)
    {
        return $this->getData(function () use ($groupId) {
            $this->compacts['data'] = $this->logRepository->getLogsByGroupId($groupId);
        });
    }
}
