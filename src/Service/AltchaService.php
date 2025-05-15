<?php

namespace App\Service;

use AltchaOrg\Altcha\Altcha;
use AltchaOrg\Altcha\Challenge;
use AltchaOrg\Altcha\ChallengeOptions;
use AltchaOrg\Altcha\Hasher\Algorithm;

class AltchaService
{
    public Altcha $altcha;
    public string $secret;

    public function __construct(string $secret)
    {
        $this->altcha = new Altcha($secret);
        $this->secret = $secret;
    }

    public function createChallenge(): Challenge
    {
        $options = new ChallengeOptions(
            maxNumber: 50000, // the maximum random number
            expires: (new \DateTimeImmutable())->add(new \DateInterval('PT10S')),
        );
        $challenge = $this->altcha->createChallenge($options);
        return $challenge;
    }

    public function verifyChallenge(string $token) : bool
    {
        // $signature = $this->altcha->getHasher()->hashHmacHex(Algorithm::SHA256, $token, $this->secret);

        $payload = [
            'algorithm' => Algorithm::SHA256,
            'challenge' => $token,
            'number'    => ChallengeOptions::DEFAULT_MAX_NUMBER, // Example number
            // 'salt'      => ChallengeOptions::DEFAULT_SALT_LENGTH,
            // 'signature' =>$signature,
        ];
        return $this->altcha->verifySolution($payload, true);
    }
}
