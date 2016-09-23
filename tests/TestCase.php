<?php

use App\User;
use Testing\Traits\Requests;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    use Requests;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Make an API call
     * @param $method
     * @param $uri
     * @param array $parameters
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param null $content
     * @return \Illuminate\Http\Response
     */
    public function apiCall($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        $headers = $this->transformHeadersToServerVars([
            'Accept' => 'application/json'
        ]);
        $server = array_merge($server, $headers);

        return parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }

    /**
     *
     * @return mixed
     */
    public function logInUser($id = 1)
    {
        $user = User::find($id);
        $this->be($user);
        $this->user = $user;
    }

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->logInUser();
    }

    /**
     *
     * @param $response
     * @return mixed
     */
    public function getContent($response)
    {
        return json_decode($response->getContent(), true);
    }

    /**
     *
     * @param $timer
     * @param null $onlyBasicKeys
     */
    protected function checkTimerKeysExist($timer, $onlyBasicKeys = null)
    {
        $this->assertArrayHasKey('id', $timer);
        $this->assertArrayHasKey('start', $timer);
        $this->assertArrayHasKey('finish', $timer);
        $this->assertArrayHasKey('startDate', $timer);
        $this->assertArrayHasKey('activity', $timer);

        $this->assertArrayHasKey('duration', $timer);
        $this->assertArrayHasKey('totalMinutes', $timer['duration']);
        $this->assertArrayHasKey('hours', $timer['duration']);
        $this->assertArrayHasKey('minutes', $timer['duration']);

        if (!isset($onlyBasicKeys)) {
            $this->assertArrayHasKey('durationForDay', $timer);
            $this->assertArrayHasKey('totalMinutes', $timer['durationForDay']);
            $this->assertArrayHasKey('hours', $timer['durationForDay']);
            $this->assertArrayHasKey('minutes', $timer['durationForDay']);
        }

    }

    /**
     *
     * @param $activity
     */
    protected function checkActivityKeysExist($activity, $weekValues = null)
    {
        $this->assertArrayHasKey('id', $activity);
        $this->assertArrayHasKey('name', $activity);

        $this->assertArrayHasKey('duration', $activity);
        $this->assertArrayHasKey('totalMinutes', $activity['duration']);
        $this->assertArrayHasKey('hours', $activity['duration']);
        $this->assertArrayHasKey('minutes', $activity['duration']);

        if ($weekValues) {
            $this->assertArrayHasKey('totalMinutes', $activity['week']);
            $this->assertArrayHasKey('averageMinutesPerDay', $activity['week']);
        }
    }
}
