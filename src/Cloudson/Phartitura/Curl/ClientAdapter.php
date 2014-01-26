<?php

namespace Cloudson\Phartitura\Curl;

interface ClientAdapter
{
    public function head($relativeURI);    
    public function setSecure($isSecure);
    public function get($relativeURI);
}