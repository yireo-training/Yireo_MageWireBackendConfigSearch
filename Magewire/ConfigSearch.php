<?php declare(strict_types=1);

namespace Yireo\MageWireBackendConfigSearch\Magewire;

use Magewirephp\Magewire\Component;
use Yireo\MageWireBackendConfigSearch\Search\Result;
use Yireo\MageWireBackendConfigSearch\Search\ResultCollector;

class ConfigSearch extends Component
{
    public string $search = '';

    public function __construct(
        private ResultCollector $resultCollector
    ) {
    }

    /**
     * @return Result[]
     */
    public function getSearchResults(): array
    {
        if (strlen($this->search) < 3) {
            return [];
        }

        return $this->resultCollector->getResults($this->search);
    }
}
