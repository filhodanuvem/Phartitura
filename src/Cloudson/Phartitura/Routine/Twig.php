<?php

namespace Cloudson\Phartitura\Routine; 

class Twig 
{
    private $twig; 

    private $globalVars = array();

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

        $data = array_merge($this->globalVars, $data);
        
        return $this->twig->render($view, $data);
    }

    public function addGlobalVar($key, $value) {
        if (!preg_match('/^\_/', $key)) {
            $key = '_' . $key;
        }

        $this->globalVars[$key] = $value;

        return $this;
    }
}