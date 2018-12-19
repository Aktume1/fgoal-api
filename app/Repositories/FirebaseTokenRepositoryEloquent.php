<?php

namespace App\Repositories;

use App\Contracts\Repositories\FirebaseTokenRepository;
use App\Eloquent\FirebaseToken;

class FirebaseTokenRepositoryEloquent extends AbstractRepositoryEloquent implements FirebaseTokenRepository
{
    public function model()
    {
        return app(FirebaseToken::class);
    }

    /**
     * Update or Create FirebaseToken
     * @param array $data
     * @return boolean
     */
    public function updateOrCreateFirebaseToken($data)
    {
        $firebaseToken = $this->model()->updateOrCreate(
            [
                'uuid' => $data['uuid'],
            ],
            [
                'user_id' => $data['user_id'],
                'token'	=> $data['token'],
            ]
        );

        $firebaseToken = $this->findOrFail($firebaseToken->id);

        if (isset($firebaseToken)) {
            return true;
        } else {
            return false;
        }
    }   
}
