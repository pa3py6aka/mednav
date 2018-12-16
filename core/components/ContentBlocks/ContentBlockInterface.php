<?php

namespace core\components\ContentBlocks;


interface ContentBlockInterface
{
    public function getFullPriceString(): string;

    public function getContentDescription(): string;

    public function getContentName(): string;

    public function getUrl(): string;

    public function getMainPhotoUrl(): string;

    public function getContentBlockRegionString(): string;
}