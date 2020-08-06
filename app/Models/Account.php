<?php

namespace App\Models;

use Sysvale\Helpers;

class Account extends \Moloquent
{
  public $fillable = [
    'name',
    'status',
    'type',
    'cnpj',
    'active',
  ];

  const TYPE_ENTERPRISE = 'E';
  const STATUS_DISABLED = 'D';
  const STATUS_ENABLED = 'E';

  public function setCnpjAttribute($value) {
    $this->attributes['cnpj'] = preg_replace('/\D/', '', $value);
  }

  public function getCnpjAttribute($value) {
    return Helpers::maskCnpj($value);
  }
}
