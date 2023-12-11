<?php
declare(strict_types=1);

namespace Yireo\MageWireBackendConfigSearch\Search;

class Result
{
    public function __construct(
        private string $path,
        private string $url
    ) {}

    public function getPath(): string
    {
        return $this->path;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
