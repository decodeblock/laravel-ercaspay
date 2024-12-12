<?php

namespace Decodeblock\Ercaspay\Types;

class PayerDeviceDto
{
    private Device $device;

    public function __construct(Device $device)
    {
        $this->device = $device;
    }

    public function toArray(): array
    {
        return [
            'payerDeviceDto' => [
                'device' => $this->device->toArray(),
            ],
        ];
    }

    // Static factory method
    public static function fromRequest($request): self
    {
        return new self(Device::fromRequest($request));
    }
}