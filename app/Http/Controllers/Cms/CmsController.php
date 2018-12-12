<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\AbstractController;
use App\Exceptions\Api\ActionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Exception;

abstract class CmsController extends AbstractController
{
    /**
     * The data api
     *
     * @var array
     */
    protected $dataAPI;

    /**
     * Get data from request API
     *
     * @param  string $api
     * @param  string $method
     * @return array
     */
    public function requestToApi($api, $method, $input = [])
    {
        try {
            $request = Request::create($api, $method, $input);
            $request->headers->set('Authorization', $this->getTokenAuthUser());

            $response = Route::dispatch($request);
            $responseAPI = $response->getData();

            $this->dataAPI = $responseAPI->data;
            
            return $this->dataAPI;
        } catch (Exception $e) {
            throw new ActionException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Get token of user loginning
     *
     * @param  string $api
     * @param  string $method
     * @return array
     */
    protected function getTokenAuthUser()
    {
        return Auth()->user()->token_verification;
    }
}
