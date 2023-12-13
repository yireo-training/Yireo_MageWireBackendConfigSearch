<?php
declare(strict_types=1);

namespace Yireo\MageWireBackendConfigSearch\Search;

use Magento\Config\Model\ResourceModel\Config\Data\CollectionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;

class ResultCollector
{
    public function __construct(
        private CollectionFactory $collectionFactory,
        private ResultFactory $resultFactory,
        private ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * @param string $search
     * @return Result[]
     */
    public function getResults(string $search): array
    {
        $results = [];
        $results = array_merge($results, $this->getScopeConfigResults($search));
        return $results;
    }

    private function getScopeConfigResults(string $search): array
    {
        $results = [];
        $values = $this->flattenArray($this->scopeConfig->getValue(''));
        foreach ($values as $path => $value) {
            if (false === stristr($path, $search)) {
                continue;
            }

            $results[] = $this->resultFactory->create([
                'path' => $path
            ]);
        }

        return $results;
    }

    private function flattenArray($array, $parentKey = '')
    {
        $result = array();
        foreach ($array as $key => $value) {
            $newKey = $parentKey.$key;
            if (is_array($value)) {
                $result = array_merge($result, $this->flattenArray($value, $newKey.'/'));
            } else {
                $result[$newKey] = $value;
            }
        }

        return $result;
    }
}
