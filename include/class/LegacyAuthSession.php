<?php

class LegacyAuthSession
{
    private string $key;

    public function __construct(string $key = 'SI_Auth')
    {
        $this->key = $key;
        if (!isset($_SESSION[$this->key])) {
            $_SESSION[$this->key] = [];
        }
    }

    public function __get(string $name)
    {
        return $_SESSION[$this->key][$name] ?? null;
    }

    public function __set(string $name, $value): void
    {
        $_SESSION[$this->key][$name] = $value;
    }

    public function __isset(string $name): bool
    {
        return isset($_SESSION[$this->key][$name]);
    }

    public function __unset(string $name): void
    {
        unset($_SESSION[$this->key][$name]);
    }

    public function refresh(): void
    {
        if (!isset($_SESSION[$this->key])) {
            $_SESSION[$this->key] = [];
        }
    }

    public function destroy(): void
    {
        unset($_SESSION[$this->key]);
    }

    public function toArray(): array
    {
        return $_SESSION[$this->key] ?? [];
    }
}
