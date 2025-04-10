<?php

namespace Winata\PackageBased\Abstracts;

use Winata\PackageBased\Concerns\ValidationInput;

/**
 * Class BaseService
 *
 * This abstract class provides a base structure for all service classes.
 * It includes validation input handling through the ValidationInput trait.
 *
 * @package Winata\PackageBased\Abstracts
 */
abstract class BaseService
{
    use ValidationInput;
}
