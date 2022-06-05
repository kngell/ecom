<?php

declare(strict_types=1);

class SlidersEntity extends Entity
{
    /** @id */
    private int $slID;
    /** @media */
    private string $media;
    private string $pageSlider;
    private string $sliderTitle;
    private string $sliderSubtitle;
    private string $sliderText;
    private string $sliderBtnText;
    private string $sliderBtnLink;
    private string $status;
    private DateTimeInterface $creadtedAt;
    private DateTimeInterface $updateAt;

    public function __construct()
    {
        $this->createdAt = !isset($this->createdAt) ? new DateTimeImmutable() : $this->createdAt;
    }
}