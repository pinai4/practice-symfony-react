<?php

declare(strict_types=1);

namespace App\ReadModel\Auth;

class AuthView
{
    public string $id;
    public string $name;
    public string $email;
    public string $password;
    public string $role;

    public function __construct(string $id, string $name, string $email, string $password, string $role)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }
}
