<?php

if (!function_exists('navActiveClass')) {
    function navActiveClass($routeName): string
    {
        return request()->route()->getName() === $routeName ? 'active' : '';
    }
}
