<?php

declare(strict_types=1);

namespace Infakt\Model\Invoice\Extension;

class Share extends AbstractExtension
{
    /**
     * @var \DateTime|null
     */
    protected $validUntil;

    /**
     * @return \DateTime|null
     */
    public function getValidUntil()
    {
        return $this->validUntil;
    }

    /**
     * @param \DateTime|null $validUntil
     *
     * @return Share
     */
    public function setValidUntil($validUntil)
    {
        $this->validUntil = $validUntil;

        return $this;
    }

    public function toArray(): array
    {
        $arrayResult = [
            'valid_until' => $this->validUntil ? $this->validUntil->format('Y-m-d') : null,
        ];

        return array_filter($arrayResult, static function ($value) {
            return null !== $value;
        });
    }
}
