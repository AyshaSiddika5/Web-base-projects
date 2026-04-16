<?php
require_once 'User.php';

class Admin extends User
{

    public function canApproveRequests(): bool
    {
        return true;
    }
}
