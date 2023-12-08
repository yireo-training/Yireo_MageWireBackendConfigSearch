<?php declare(strict_types=1);

namespace Yireo\MageWireBackendConfigSearch\Plugin;

use Closure;
use Magento\Config\Model\Config\Structure;
use Magento\Config\Model\Config\Structure\Element\Section;
use Magento\Paypal\Model\Config\StructurePlugin;

class FixPathPartsPayPal
{
    /**
     * @param StructurePlugin $structurePlugin
     * @param Structure $structure
     * @param Closure $proceed
     * @param array $pathParts
     * @return array
     */
    public function beforeAroundGetElementByPathParts(
        StructurePlugin $structurePlugin,
        Structure $structure,
        Closure $proceed,
        array $pathParts
    ): array {
        if (!isset($pathParts[0])) {
            $pathParts[0] = null;
        }

        return [$structure, $proceed, $pathParts];
    }
}