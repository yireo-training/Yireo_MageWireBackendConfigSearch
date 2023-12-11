<?php
declare(strict_types=1);

namespace Yireo\MageWireBackendConfigSearch\Search;

use Magento\Backend\Model\UrlFactory;
use Magento\Config\Model\Config\Structure;
use Magento\Config\Model\ResourceModel\Config\Data\CollectionFactory;
use Magento\Config\Model\ResourceModel\Config\Data\Collection;
use Magento\Framework\App\Config\Value;

class ResultCollector
{
    public function __construct(
        private Structure $configStructure,
        private CollectionFactory $collectionFactory,
        private UrlFactory $urlFactory,
        private ResultFactory $resultFactory
    ) {
    }

    /**
     * @param string $search
     * @return Result[]
     */
    public function getResults(string $search): array
    {
        $results = [];
        //$results = array_merge($results, $this->getFieldsResults($search));
        $results = array_merge($results, $this->getConfigValueResults($search));
        return $results;
    }

    /**
     * @param string $search
     * @return Result[]
     */
    private function getFieldsResults(string $search): array
    {
        $results = [];
        $fieldPaths = $this->configStructure->getFieldPaths();
        foreach ($fieldPaths as $fieldPath) {
            foreach ($fieldPath as $field) {
                if (!stristr($field, $search)) {
                    continue;
                }

                $pathParts = explode('/', $field);
                $section = reset($pathParts);
                $results[] = $this->resultFactory->create([
                    'path' => $field,
                    'url' => $this->getUrl($section),
                ]);
            }
        }

        return $results;
    }

    /**
     * @param string $search
     * @return @return Result[]
     */
    private function getConfigValueResults(string $search): array
    {
        $results = [];
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('path', ['like' => '%'.$search.'%']);

        /** @var Value $configValue */
        foreach ($collection as $configValue) {
            $path = $configValue->getPath();
            $pathParts = explode('/', $path);
            $section = reset($pathParts);

            $results[] = $this->resultFactory->create([
                'path' => $configValue->getPath(),
                'url' => $this->getUrl($section),
            ]);
        }

        return $results;
    }

    /**
     * @param string $section
     * @return string
     */
    private function getUrl(string $section): string
    {
        $routePath = 'admin/system_config/edit/section/' . $section;
        return $this->urlFactory->create()->getUrl($routePath);
    }
}
