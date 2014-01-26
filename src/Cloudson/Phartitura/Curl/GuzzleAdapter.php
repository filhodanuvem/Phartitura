<?php

namespace Cloudson\Phartitura\Curl;

use \Guzzle\Http\Client as Guzzle;

class GuzzleAdapter implements ClientAdapter
{
    private $c; 
    private $isSecure;

    public function __construct(Guzzle $guzzle)
    {
        $this->c = $guzzle;
    }


    public function head($relativeURI)
    {
        $request = $this->c->head($relativeURI);
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

    public function isSecure()
    {
        return $this->isSecure;
    }

    public function get($relativeURI)
    {
        $request = $this->c->get($relativeURI);
        $response = $request->send();

        return new GuzzleResponseAdapter($response);
    }
}