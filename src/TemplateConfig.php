<?php

class TemplateConfig
{
    public $Options = [];

    public $required = [
        'template_dir',
        'cache_dir',
        'debug',
        'user_dir',
    ];

    public function __construct($options = [])
    {
        foreach ($options as $key => $value) {
            $this->set($key, $value);
        }
        $this->validateRequired();
    }

    public function set($key, $value)
    {
        $this->Options[$key] = $value;
    }

    public function get($key, $default = null)
    {
        return $this->Options[$key] ?? $default;
    }

    public function all()
    {
        return $this->Options;
    }

    public function has($key)
    {
        return isset($this->Options[$key]);
    }

    public function remove($key)
    {
        unset($this->Options[$key]);
    }

    public function clear()
    {
        $this->Options = [];
    }

    public function verify($key, $value)
    {
        // Implement verification logic here
        return true; // Placeholder for actual verification result
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function __set($key, $value)
    {
        if ($this->verify($key, $value)) {
            $this->set($key, $value);
        } else {
            // Handle invalid option value
            throw new InvalidArgumentException("Invalid value for option '{$key}'");
        }
    }

    public function validateRequired()
    {
        foreach ($this->required as $key) {
            if (!$this->has($key)) {
                throw new InvalidArgumentException("Missing required option '{$key}'");
            }
        }
    }
}
