<?php

namespace App\Http\Controllers\Api;

use App\Eloquent\FirebaseToken;
use Illuminate\Http\Request;
use App\Contracts\Repositories\FirebaseTokenRepository;
use GuzzleHttp\Exception\RequestException;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class FirebaseController extends ApiController
{
    protected $firebaseTokenRepository;

    /**
     * The HTTP Client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * Create a new controller instance.
     * @return void
     **/
    public function __construct(FirebaseTokenRepository $firebaseTokenRepository)
    {
        parent::__construct();
        $this->firebaseTokenRepository = $firebaseTokenRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only(
            'user_id',
            'uuid',
            'token'
        );

        return $this->doAction(function () use ($data) {
            $this->compacts['data'] = $this->firebaseTokenRepository->updateOrCreateFirebaseToken($data);
            $this->compacts['description'] = translate('success.create');
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FirebaseToken  $firebaseToken
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FirebaseToken  $firebaseToken
     * @return \Illuminate\Http\Response
     */
    public function edit(FirebaseToken $firebaseToken)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FirebaseToken  $firebaseToken
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FirebaseToken $firebaseToken)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FirebaseToken  $firebaseToken
     * @return \Illuminate\Http\Response
     */
    public function destroy(FirebaseToken $firebaseToken)
    {
        //
    }

    public function request(callable $request)
    {
        try {
            $response = call_user_func($request);
            return json_decode($response->getBody());
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();

                return json_decode($response->getBody());
            }
            throw new Exception('RequestException');
        }
    }

    /**
     * Send notification for one specified user.
     * @param Id of user
     * @return \GuzzleHttp\Client
     */
    public function send($userId)
    {
        $firebaseToken = $this->firebaseTokenRepository->where('user_id', $userId)->firstOrFail();
        $device_token = $firebaseToken->token;
        $message_json = [
            'to' => $device_token,
            'notification' => [
                'title' => 'Test',
                'body' => 'Hello World',
            ]
        ];

        $response = $this->request(function () use ($message_json) {
            return $this->getHttpClient()->request('POST', config('services.fcm.url'), [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key=' . env('LEGACY_SERVER_KEY'),
                ],

                'json' => $message_json,
            ]);
        });

        return json_decode(json_encode($response), true);
    }

    /**
     * Get a instance of the Guzzle HTTP client.
     *
     * @return \GuzzleHttp\Client
     */
    protected function getHttpClient()
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = new Client();
        }

        return $this->httpClient;
    }
}
