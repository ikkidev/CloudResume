<?php

namespace WPGDPRC\WordPress\Ajax;

use WPGDPRC\Utils\UserTypeHelper;
use WPGDPRC\WordPress\Plugin;

class FormSubmitted extends AbstractAjax
{
    /**
     * Returns AJAX action name
     * @return string
     */
    protected static function getAction(): string
    {
        return Plugin::PREFIX . '_form_submitted';
    }

    public static function hasData(): bool
    {
        return false;
    }

    public static function buildResponse($data = [])
    {
        UserTypeHelper::setFormSubmitted(true);

        static::returnSuccess('success');
    }
}
