<?php
/**
 * Created by PhpStorm.
 * User: Ricoru
 * Date: 26/12/17
 * Time: 16:31
 */

namespace App;

class Token
{
    public $decoded;
    public function hydrate($decoded)
    {
        $this->decoded = $decoded;
    }
    public function hasScope(array $scope)
    {
        return !!count(array_intersect($scope, $this->decoded->scope));
    }
}