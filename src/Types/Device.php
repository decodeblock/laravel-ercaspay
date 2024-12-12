<?php

namespace Decodeblock\Ercaspay\Types;

class Device
{
    private string $browser;

    private BrowserDetail $browserDetails;

    private string $ipAddress;

    public function __construct(
        string $browser,
        BrowserDetail $browserDetails,
        string $ipAddress
    ) {
        $this->browser = $browser;
        $this->browserDetails = $browserDetails;
        $this->ipAddress = $ipAddress;
    }

    public function toArray(): array
    {
        return [
            'browser' => $this->browser,
            'browserDetails' => $this->browserDetails->toArray(),
            'ipAddress' => $this->ipAddress,
        ];
    }

    // Static factory method
    public static function fromRequest($request): self
    {
        return new self(
            $request->userAgent() ?? 'server',
            BrowserDetail::fromRequest($request),
            $request->ip() ?? '127.0.0.1'
        );
    }
}
