<?php

namespace Cloudson\Phartitura\Curl;

class GuzzleAdapter implements ClientAdapter
{
    private $c; 

    public function __construct(Guzzle $guzzle)
    {
        $this->c = $guzzle;
    }


    public function head()
    {
        $request = $c->head('/');
        $response = $request->send();

        return new GuzzleResponseAdapter($response);
    }
}