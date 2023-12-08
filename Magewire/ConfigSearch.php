<?php declare(strict_types=1);

namespace Yireo\MageWireBackendConfigSearch\Magewire;

use Magento\Backend\Model\UrlFactory;
use Magento\Config\Model\ResourceModel\Config\Data\CollectionFactory;
use Magento\Config\Model\ResourceModel\Config\Data\Collection;
use Magento\Framework\App\Config\Value;
use Magewirephp\Magewire\Component;

class ConfigSearch extends Component
{
    public string $search = '';

    public function __construct(
        private CollectionFactory $collectionFactory,
        private UrlFactory $urlFactory
    ) {
    }

    public function getSearchResults(): ?Collection
    {
        if (strlen($this->search) < 3) {
            return null;
        }

        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('path', ['like' => '%'.$this->search.'%']);
        return $collection;
    }

    /**
     * @param Value $configValue
     * @return string
     */
    public function getUrl(Value $configValue): string
    {
        $path = $configValue->getPath();
        $pathParts = explode('/', $path);
        $section = reset($pathParts);
        $routePath = 'admin/system_config/edit/section/' . $section;
        return $this->urlFactory->create()->getUrl($routePath);
    }
}
