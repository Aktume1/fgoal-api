<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Contracts\Repositories\WebhookRepository;

class WebhookController extends ApiController
{
    protected $webhookRepository;

    public function __construct(WebhookRepository $webhookRepository)
    {
        parent::__construct();
        $this->webhookRepository = $webhookRepository;
    }

    public function handleWebhookWSM(Request $request)
    {
        $dataWebhook = json_decode($request->getContent(), true);

        return $this->doAction(function () use ($dataWebhook) {
            $this->compacts['data'] = $this->webhookRepository->handleWebhookWSM($dataWebhook);
        });
    }
}
