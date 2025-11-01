<?php

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Label\Label;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Illuminate\Support\Facades\Log;
use Endroid\QrCode\Writer\PngWriter;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Endroid\QrCode\Encoding\Encoding;
use Lcobucci\JWT\Signer\Key\InMemory;
use Endroid\QrCode\RoundBlockSizeMode;
use Lcobucci\JWT\Validation\Constraint;
use Endroid\QrCode\ErrorCorrectionLevel;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;

if (! function_exists('createQRCode')) {
    function createQRCode($str)
    {
        $writer = new PngWriter();
        $qrCode = new QrCode(
            data: $str,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Low,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255)
        );
        $logo = new Logo(
            path: public_path('assets/icons/two-factor-authentication.png'),
            resizeToWidth: 50,
            punchoutBackground: true
        );
        $result = $writer->write($qrCode, $logo);
        return '<img src="' . $result->getDataUri() . '" style="width: 100%; height: 100%;" />';
    }
}
if (! function_exists('jwtEncode')) {
    function jwtEncode(array $claims, int $ttlSeconds = 3600): string
    {
        $config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText(env('JWT_SECRET'))
        );

        $now = new \DateTimeImmutable();

        $builder = $config->builder()
            ->issuedBy(config('app.url'))
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify("+{$ttlSeconds} seconds"));

        foreach ($claims as $key => $value) {
            $builder = $builder->withClaim($key, $value);
        }

        $token = $builder->getToken($config->signer(), $config->signingKey());

        return $token->toString();
    }
}

if (! function_exists('jwtDecode')) {
    function jwtDecode(string $jwt): ?array
    {
        $config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText(env('JWT_SECRET'))
        );

        try {
            $token = $config->parser()->parse($jwt);

            $constraints = [
                new Constraint\SignedWith($config->signer(), $config->verificationKey()),
                new Constraint\StrictValidAt(SystemClock::fromSystemTimezone())
            ];

            $config->validator()->assert($token, ...$constraints);

            return $token->claims()->all();
        } catch (\Throwable $e) {
            Log::error('JWT Error: ' . $e->getMessage());
            abort(419, 'The page has expired due to inactivity. Please refresh and try again.');
        }
    }
}