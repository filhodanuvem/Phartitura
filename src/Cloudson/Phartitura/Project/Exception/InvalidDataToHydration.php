<?php

namespace Cloudson\Phartitura\Project\Exception;

class InvalidDataToHydration extends \BadMethodCallException
{
    const REASON_EMPTY = 1;
    const REASON_NOT_ARRAY = 2;
    const REASON_OUT_OF_FORMAT = 3;
}
