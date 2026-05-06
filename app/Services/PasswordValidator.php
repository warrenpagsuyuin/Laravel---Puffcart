<?php

namespace App\Services;

class PasswordValidator
{
    protected array $commonPasswords = [
        'password',
        'password123',
        'admin123',
        'qwerty123',
        '12345678',
        'letmein',
        'welcome',
        'sunshine',
        'football',
        'master',
    ];

    /**
     * Validate password against common password list
     */
    public function isCommon(string $password): bool
    {
        return in_array(strtolower($password), array_map('strtolower', $this->commonPasswords));
    }

    /**
     * Check if password meets security requirements
     * Returns array with status and message
     */
    public function validate(string $password): array
    {
        // Check length
        if (strlen($password) < 8) {
            return ['valid' => false, 'message' => 'Password must be at least 8 characters long.'];
        }

        // Check for uppercase
        if (!preg_match('/[A-Z]/', $password)) {
            return ['valid' => false, 'message' => 'Password must contain at least one uppercase letter.'];
        }

        // Check for lowercase
        if (!preg_match('/[a-z]/', $password)) {
            return ['valid' => false, 'message' => 'Password must contain at least one lowercase letter.'];
        }

        // Check for number
        if (!preg_match('/[0-9]/', $password)) {
            return ['valid' => false, 'message' => 'Password must contain at least one number.'];
        }

        // Check for special character
        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};:\'",.<>?\/\\|`~]/', $password)) {
            return ['valid' => false, 'message' => 'Password must contain at least one special character.'];
        }

        // Check against common passwords
        if ($this->isCommon($password)) {
            return ['valid' => false, 'message' => 'This password is too common. Please choose a stronger password.'];
        }

        return ['valid' => true, 'message' => 'Password is valid.'];
    }
}
