<?php

declare(strict_types=1);

namespace Infakt\Model\Invoice\Extension;

interface ExtensionInterface
{
    public function getLink(): string;

    public function setLink(?string $link);

    public function isAvailable(): bool;

    public function setAvailable(bool $isAvailable);

    public function toArray(): array;
}
