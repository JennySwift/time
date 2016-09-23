<?php

namespace Testing\Traits;

use Illuminate\Http\Response;

trait Requests
{
    /**
     *
     * @param $url
     * @param $data
     * @param $responseCode
     * @return mixed
     */
    public function store($url, $data, $responseCode = 201)
    {
        $response = $this->apiCall('POST', $url, $data);
        $content = $this->getContent($response);
//        dd($content);
        $this->assertResponseStatus($responseCode);

        return $content;
    }

    /**
     *
     * @param $url
     * @param $data
     * @param $responseCode
     * @return mixed
     */
    public function update($url, $data, $responseCode = 200)
    {
        $response = $this->apiCall('PUT', $url, $data);
        $content = $this->getContent($response);
        $this->assertResponseStatus($responseCode);

        return $content;
    }

    /**
     *
     * @param $url
     * @param null $responseCode
     * @return mixed
     */
    public function destroy($url, $responseCode = null)
    {
        $response = $this->call('DELETE', $url);

        if (!$responseCode) {
            $this->assertEquals(204, $response->getStatusCode());
            $response = $this->call('DELETE', $url);
            $this->assertEquals(404, $response->getStatusCode());
        }
        else {
            //For testing that an item in use can't be deleted
            $this->assertEquals($responseCode, $response->getStatusCode());
        }

        return $this->getContent($response);
    }

    /**
     *
     * @param $url
     */
    public function detach($url)
    {
        $response = $this->call('DELETE', $url);
        $this->assertEquals(204, $response->getStatusCode());
    }

    /**
     *
     * @param $url
     * @param int $responseCode
     * @return mixed
     */
    public function show($url, $responseCode = 200)
    {
        $response = $this->call('GET', $url);
        $content = $this->getContent($response);
//        dd($content);

        $this->assertEquals($responseCode, $response->getStatusCode());

        return $content;
    }

    /**
     *
     * @param $url
     */
    public function destroyUnauthorized($url)
    {
        $response = $this->call('DELETE', $url);
        $content = $this->getContent($response);

        $this->assertEquals('Unauthorised', $content['error']);

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    /**
     *
     * @param $url
     */
    public function showUnauthorized($url)
    {
        $response = $this->call('GET', $url);
        $content = $this->getContent($response);
//        dd($content);

        $this->assertArrayHasKey('error', $content);
        $this->assertContains('Unauthorised', $content['error']);

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    /**
     *
     * @param $url
     */
    public function showForbidden($url)
    {
        $response = $this->call('GET', $url);
        $content = $this->getContent($response);

        $this->assertArrayHasKey('error', $content);
        $this->assertContains('Forbidden', $content['error']);

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     *
     * @param $url
     */
    public function updateUnauthorized($url)
    {
        $response = $this->call('PUT', $url);
        $content = $this->getContent($response);
        //        dd($content);

        $this->assertArrayHasKey('error', $content);
        $this->assertContains('Unauthorised', $content['error']);

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    /**
     *
     * @param $url
     */
    public function updateForbidden($url)
    {
        $response = $this->call('PUT', $url);
        $content = $this->getContent($response);
        //        dd($content);

        $this->assertArrayHasKey('error', $content);
        $this->assertContains('Forbidden', $content['error']);

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

}