<?php
require_once 'User.php';

class Donor extends User
{

    public function canCreateDonation(): bool
    {
        return true;
    }
}
