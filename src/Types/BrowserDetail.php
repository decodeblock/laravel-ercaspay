<?php

/**
* Author: Gabriel Ibenye
* GitHub: https://github.com/gabbyTI
* Email: gabrielibenye@gmail.com
* Created: December 11, 2024
*/


namespace Decodeblock\Ercaspay\Types;

class BrowserDetail
{
    private string $challengeWindowSize;

    private string $acceptHeaders;

    private int $colorDepth;

    private bool $javaEnabled;

    private string $language;

    private int $screenHeight;

    private int $screenWidth;

    private int $timeZone;

    public function __construct(
        string $challengeWindowSize = 'FULL_SCREEN',
        string $acceptHeaders = 'application/json',
        int $colorDepth = 24,
        bool $javaEnabled = true,
        string $language = 'en-US',
        int $screenHeight = 473,
        int $screenWidth = 1600,
        int $timeZone = 273
    ) {
        $this->challengeWindowSize = $challengeWindowSize;
        $this->acceptHeaders = $acceptHeaders;
        $this->colorDepth = $colorDepth;
        $this->javaEnabled = $javaEnabled;
        $this->language = $language;
        $this->screenHeight = $screenHeight;
        $this->screenWidth = $screenWidth;
        $this->timeZone = $timeZone;
    }

    public function toArray(): array
    {
        return [
            '3DSecureChallengeWindowSize' => $this->challengeWindowSize,
            'acceptHeaders' => $this->acceptHeaders,
            'colorDepth' => $this->colorDepth,
            'javaEnabled' => $this->javaEnabled,
            'language' => $this->language,
            'screenHeight' => $this->screenHeight,
            'screenWidth' => $this->screenWidth,
            'timeZone' => $this->timeZone,
        ];
    }

    // Getters
    public function getChallengeWindowSize(): string
    {
        return $this->challengeWindowSize;
    }

    public function getAcceptHeaders(): string
    {
        return $this->acceptHeaders;
    }

    public function getColorDepth(): int
    {
        return $this->colorDepth;
    }

    public function isJavaEnabled(): bool
    {
        return $this->javaEnabled;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getScreenHeight(): int
    {
        return $this->screenHeight;
    }

    public function getScreenWidth(): int
    {
        return $this->screenWidth;
    }

    public function getTimeZone(): int
    {
        return $this->timeZone;
    }

    // Static factory method
    public static function fromRequest($request): self
    {
        return new self(
            'FULL_SCREEN',
            $request->header('Accept', 'application/json'),
            $request->input('colorDepth', 24),
            $request->input('javaEnabled', true),
            $request->getPreferredLanguage() ?? 'en-US',
            $request->input('screenHeight', 473),
            $request->input('screenWidth', 1600),
            $request->input('timeZone', 273)
        );
    }
}
