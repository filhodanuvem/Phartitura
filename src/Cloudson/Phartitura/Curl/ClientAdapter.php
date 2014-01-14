<?php

namespace Cloudson\Phartitura\Curl;

interface ClientAdapter
{
    public function head();    
    public function setSecure($isSecure);
}