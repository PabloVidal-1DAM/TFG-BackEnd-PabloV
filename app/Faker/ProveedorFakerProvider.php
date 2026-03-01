<?php

namespace App\Faker;

use Faker\Provider\Base;

class ProveedorFakerProvider extends Base
{
    public function cif():string{
        // Genera un string que consta de una letra mayúscula específica + 7 números + 1 letra o número.
        return $this->generator->regexify('[A-HJ-NP-SUVW][0-9]{7}[0-9A-J]');
    }
}
