<?php

namespace Cloudson\Phartitura\Curl; 

interface ResponseAdapter
{
    public function getStatusCode();
    public function getBody();
    public function getHeader();
    public function getHeaderInfo($info);
}