<?php

/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2015 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 */

namespace oat\taoItems\model\asset;

use \core_kernel_classes_Resource;
use \taoItems_models_classes_ItemsService;

/**
 * To allow packing of Item. The goal of the packaging is to reprensent the data needed
 * to run an item (ie. an ItemPack).
 *
 * @author Bertrand Chevrier <bertrand@taotesting.com>
 */
class Loader
{

    /**
     * The item to pack
     * @var core_kernel_classes_Resource
     */
    private $item;

    /**
     * The lang of the item to pack
     * @var string
     */
    private $lang;

    /**
     * The item service
     * @var taoItems_models_classes_ItemsService
     */
    private $itemService;

    /**
     * Create a packer for an item
     * @param core_kernel_classes_Resource $item
     */
    public function __construct(core_kernel_classes_Resource $item, $lang = ''){
        $this->item = $item;
        $this->lang = $lang;
        $this->itemService = taoItems_models_classes_ItemsService::singleton();
    }

    /**
     * Get the content of an asset.
     * Only LOCAL assets are yet supported.
     *
     * @param string $assetPath the asset path
     * @return null|string the asset content or null if not stored locally
     * @throws common_exception_Error if the resource cannot be retrieved
     */
    public function getAssetContent($assetPath)
    {
        $parsedUrl = parse_url($assetPath);

        //check rel url
        if(!empty($assetPath) && array_key_exists('path', $parsedUrl) && !array_key_exists('host', $parsedUrl)){
            $folder = $this->itemService->getItemFolder($this->item, $this->lang);
            if(tao_helpers_File::securityCheck($assetPath, true)){
                $filename = $folder.$assetPath;
                if(file_exists($filename)){
                    return file_get_contents($filename);
                } else {
                    throw new common_exception_Error('The unable to retrieve content of asset ' . $assetPath . ' at ' . $filename );
                }
            } else {
                throw new common_exception_Error('A file path did not match the security policy');
            }
        }

        //does not yet support remote resources
        return null;
    }
}
