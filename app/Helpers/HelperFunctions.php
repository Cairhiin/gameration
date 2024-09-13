<?php

if (!function_exists('getAuthUser')) {
    /**
     * @return \App\Models\User|null
     */
    function getAuthUser(): ?\App\Models\User
    {
        /** @var \App\Models\User */
        return auth()->user();
    }
}
