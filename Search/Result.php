<?php
declare(strict_types=1);

namespace Yireo\MageWireBackendConfigSearch\Search;

use Magento\Backend\Model\UrlFactory;
use Magento\Config\Model\Config\Structure as ConfigStructure;
use Magento\Config\Model\Config\Structure\Element\Section;
use Yireo\MageWireBackendConfigSearch\Exception\NoSuchSectionException;

class Result
{
    public function __construct(
        private ConfigStructure $configStructure,
        private UrlFactory $urlFactory,
        private string $path
    ) {
    }

    public function getPath(): string
    {
        return $this->path;
    }


    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->urlFactory->create()->getUrl('adminhtml/system_config', [
            'section' => $this->getSectionName(),
            'group' => $this->getGroupName(),
        ]);
    }

    /**
     * @return string
     */
    private function getSectionName(): string
    {
        $pathParts = explode('/', $this->path);
        return $pathParts[0];
    }


    /**
     * @return string
     */
    private function getGroupName(): string
    {
        $pathParts = explode('/', $this->path);
        return $pathParts[1];
    }

    /**
     * @param string $sectionName
     * @return Section
     * @throws NoSuchSectionException
     */
    public function getSection(): ?Section
    {
        $sectionName = $this->getSectionName();
        foreach ($this->configStructure->getTabs() as $tab) {
            /** @var Section $section */
            foreach ($tab->getChildren() as $section) {
                if (false === $section->isAllowed()) {
                    continue;
                }

                if (false === $section->isVisible()) {
                    continue;
                }

                if (trim($section->getPath(), '/') === $sectionName) {
                    return $section;
                }
            }
        }

        return null;
    }
}
