<?php

/**
 * LICENSE: The MIT License (the "License")
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * https://github.com/azure/azure-storage-php/LICENSE
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 * PHP version 5
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Blob\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
 
namespace MicrosoftAzure\Storage\Blob\Models;
use MicrosoftAzure\Storage\Common\Internal\Validate;

/**
 * Optional parameters for commitBlobBlocks
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Blob\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @version   Release: 0.11.0
 * @link      https://github.com/azure/azure-storage-php
 */
class CommitBlobBlocksOptions extends BlobServiceOptions
{
     /**
      * @var string
      */
    private $_blobContentType;
    
    /**
     * @var string
     */
    private $_blobContentEncoding;
    
    /**
     * @var string
     */
    private $_blobContentLanguage;
    
    /**
     * @var string
     */
    private $_blobContentMD5;
    
    /**
     * @var string
     */
    private $_blobCacheControl;
    
    /**
     * @var array
     */
    private $_metadata;
    
    /**
     * @var string
     */
    private $_leaseId;
    
    /**
     * @var AccessCondition
     */
    private $_accessCondition;
    
    /**
     * Gets blob ContentType.
     *
     * @return string.
     */
    public function getBlobContentType()
    {
        return $this->_blobContentType;
    }

    /**
     * Sets blob ContentType.
     *
     * @param string $blobContentType value.
     *
     * @return none.
     */
    public function setBlobContentType($blobContentType)
    {
        $this->_blobContentType = $blobContentType;
    }
    
    /**
     * Gets blob ContentEncoding.
     *
     * @return string.
     */
    public function getBlobContentEncoding()
    {
        return $this->_blobContentEncoding;
    }

    /**
     * Sets blob ContentEncoding.
     *
     * @param string $blobContentEncoding value.
     *
     * @return none.
     */
    public function setBlobContentEncoding($blobContentEncoding)
    {
        $this->_blobContentEncoding = $blobContentEncoding;
    }
    
    /**
     * Gets blob ContentLanguage.
     *
     * @return string.
     */
    public function getBlobContentLanguage()
    {
        return $this->_blobContentLanguage;
    }

    /**
     * Sets blob ContentLanguage.
     *
     * @param string $blobContentLanguage value.
     *
     * @return none.
     */
    public function setBlobContentLanguage($blobContentLanguage)
    {
        $this->_blobContentLanguage = $blobContentLanguage;
    }
    
    /**
     * Gets blob ContentMD5.
     *
     * @return string.
     */
    public function getBlobContentMD5()
    {
        return $this->_blobContentMD5;
    }

    /**
     * Sets blob ContentMD5.
     *
     * @param string $blobContentMD5 value.
     *
     * @return none.
     */
    public function setBlobContentMD5($blobContentMD5)
    {
        $this->_blobContentMD5 = $blobContentMD5;
    }
    
    /**
     * Gets blob cache control.
     *
     * @return string.
     */
    public function getBlobCacheControl()
    {
        return $this->_blobCacheControl;
    }
    
    /**
     * Sets blob cacheControl.
     *
     * @param string $blobCacheControl value to use.
     * 
     * @return none.
     */
    public function setBlobCacheControl($blobCacheControl)
    {
        $this->_blobCacheControl = $blobCacheControl;
    }
    
    /**
     * Gets access condition
     * 
     * @return AccessCondition
     */
    public function getAccessCondition()
    {
        return $this->_accessCondition;
    }
    
    /**
     * Sets access condition
     * 
     * @param AccessCondition $accessCondition value to use.
     * 
     * @return none.
     */
    public function setAccessCondition($accessCondition)
    {
        $this->_accessCondition = $accessCondition;
    }
    
    /**
     * Gets blob metadata.
     *
     * @return array.
     */
    public function getMetadata()
    {
        return $this->_metadata;
    }

    /**
     * Sets blob metadata.
     *
     * @param array $metadata value.
     * 
     * @return none.
     */
    public function setMetadata($metadata)
    {
        $this->_metadata = $metadata;
    }
    
    /**
     * Gets lease Id for the blob
     * 
     * @return string
     */
    public function getLeaseId()
    {
        return $this->_leaseId;
    }
    
    /**
     * Sets lease Id for the blob
     * 
     * @param string $leaseId the blob lease id.
     * 
     * @return none
     */
    public function setLeaseId($leaseId)
    {
        $this->_leaseId = $leaseId;
    }
}


