<?php

namespace App\Enums;

abstract class SystemMessage
{
    public const UPDATE_SUCCESS = ' updated successfully.';
    public const UPDATE_FAILURE = ' update failed. Please try again.';
    public const STORE_SUCCESS  = ' created successfully.';
    public const STORE_FAILURE  = ' creation failed. Please try again.';
    public const DELETE_SUCCESS = ' deleted successfully.';
    public const DELETE_FAILURE = ' deletion failed. Please try again.';
}
