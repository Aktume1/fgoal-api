<?php

namespace App\Contracts\Repositories;

interface WebhookRepository extends AbstractRepository
{
    public function handleWebhookWSM($dataWebhook);

    public function updateOrCreateUserWSM($dataWebhook);

    public function deleteUserWSM($dataWebhook);

    public function updateOrCreateGroupWSM($dataWebhook);

    public function deleteGroupWSM($dataWebhook);

    public function assignUserToGroup($dataWebhook);

    public function unassignUserToGroup($dataWebhook);
}
