<?php

namespace Cloudson\Phartitura\Curl;

class GuzzleAdapter implements ClientAdapter
{
    private $c; 
    private $isSecure;

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

    public function setSecure($isSecure)
    {
        if (is_bool($isSecure)) {
            throw new \InvalidArgumentException("Security may be a boolean");
        }

        $this->isSecure = $isSecure;
    }
}