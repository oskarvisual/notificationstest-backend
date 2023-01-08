<?php

namespace App\Domain\Observer;

interface Observer
{
    public function update(array $observer): void;
}
