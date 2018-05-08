<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 08/05/2018
 * Time: 18:49
 */

namespace App\Twig;


class FiltersExtension extends \Twig_Extension
{
    public function getFilters() {
        return array(
            'json_decode'   => new \Twig_Filter('jsonDecode', [$this, 'jsonDecode']),
        );
    }

    public function jsonDecode($str) {
        return json_decode($str);
    }
}