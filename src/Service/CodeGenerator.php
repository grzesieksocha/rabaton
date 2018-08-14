<?php declare(strict_types=1);

namespace App\Service;

use Closure;

class CodeGenerator
{
    private $codesQuantity;
    private $codeLength;

    /**
     * @param mixed $codesQuantity
     */
    public function setCodesQuantity(int $codesQuantity)
    {
        $this->codesQuantity = $codesQuantity;
    }

    public function setCodeLength(int $codeLength)
    {
        $this->codeLength = $codeLength;
    }

    public function getGenerator() : Closure
    {
        $codeLength = $this->codeLength;
        $codesQuantity = $this->codesQuantity;

        return
            function() use ($codeLength, $codesQuantity) {
                $codeAlphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $codeAlphabet .= 'abcdefghijklmnopqrstuvwxyz';
                $codeAlphabet .= '0123456789';
                $length = strlen($codeAlphabet);

                for ($i = 0; $i < $codesQuantity; $i++) {
                    $token = "";
                    while (strlen($token) < $codeLength) {
                        $token .= $codeAlphabet[random_int(0, $length-1)];
                    }

                    yield $token;
                }
            };
    }
}
