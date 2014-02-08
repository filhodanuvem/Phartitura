<?php

namespace Cloudson\Phartitura\Routine; 

class Json 
{

    public function __invoke($data)
    {
        unset($data['_view']);


        return json_encode($data);
    }
}