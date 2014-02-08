<?php

namespace Cloudson\Phartitura\Routine; 

class Twig 
{
    private $twig; 

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke($data)
    {
        if (!is_array($data)) {
            return (string)$data;
        }

        if (!isset($data['_view'])) {
            throw new \InvalidArgumentException("_view not found");   
        }

        $view = $data['_view'];
        unset($data['_view']);

        return $this->twig->render($view, $data);
    }
}