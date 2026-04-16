<?php
require_once 'User.php';

class NGO extends User
{

    public function canRequestDonation(): bool
    {
        return true;
    }
}
