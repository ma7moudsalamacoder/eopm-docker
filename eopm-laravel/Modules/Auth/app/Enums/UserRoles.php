<?php

namespace Modules\Auth\Enums;

enum UserRoles : string {
    case ADMIN = "Administrator";
    case CUSTOMER = "Customer";

    public static function tryByName(string $name): ?UserRoles {
        foreach (UserRoles::cases() as $role) {
            if ($role->name === strtoupper($name)) {
                return $role;
            }
        }
        return null;
    }
}
