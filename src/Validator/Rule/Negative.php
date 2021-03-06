<?php
/**
 * Bluz Framework Component
 *
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/framework
 */

/**
 * @namespace
 */
namespace Bluz\Validator\Rule;

/**
 * Check for negative number
 *
 * @package Bluz\Validator\Rule
 */
class Negative extends AbstractRule
{
    /**
     * @var string error template
     */
    protected $template = '{{name}} must be negative';

    /**
     * Check for negative number
     *
     * @param  string $input
     * @return bool
     */
    public function validate($input) : bool
    {
        return $input < 0;
    }
}
