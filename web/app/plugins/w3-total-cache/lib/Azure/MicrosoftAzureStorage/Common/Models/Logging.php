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
 * @package   MicrosoftAzure\Storage\Common\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
 
namespace MicrosoftAzure\Storage\Common\Models;
use MicrosoftAzure\Storage\Common\Models\RetentionPolicy;
use MicrosoftAzure\Storage\Common\Internal\Utilities;

/**
 * Holds elements of queue properties logging field.
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Common\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @version   Release: 0.11.0
 * @link      https://github.com/azure/azure-storage-php
 */
class Logging
{
    /**
     * The version of Storage Analytics to configure
     * 
     * @var string
     */
    private $_version;
    
    /**
     * Applies only to logging configuration. Indicates whether all delete requests 
     * should be logged.
     * 
     * @var bool
     */
    private $_delete;
    
    /**
     * Applies only to logging configuration. Indicates whether all read requests 
     * should be logged.
     * 
     * @var bool.
     */
    private $_read;
    
    /**
     * Applies only to logging configuration. Indicates whether all write requests 
     * should be logged.
     * 
     * @var bool 
     */
    private $_write;
    
    /**
     * @var MicrosoftAzure\Storage\Common\Models\RetentionPolicy
     */
    private $_retentionPolicy;
    
    /**
     * Creates object from $parsedResponse.
     * 
     * @param array $parsedResponse XML response parsed into array.
     * 
     * @return MicrosoftAzure\Storage\Common\Models\Logging
     */
    public static function create($parsedResponse)
    {
        $result = new Logging();
        $result->setVersion($parsedResponse['Version']);
        $result->setDelete(Utilities::toBoolean($parsedResponse['Delete']));
        $result->setRead(Utilities::toBoolean($parsedResponse['Read']));
        $result->setWrite(Utilities::toBoolean($parsedResponse['Write']));
        $result->setRetentionPolicy(
            RetentionPolicy::create($parsedResponse['RetentionPolicy'])
        );
        
        return $result;
    }
    
    /**
     * Gets retention policy
     * 
     * @return MicrosoftAzure\Storage\Common\Models\RetentionPolicy
     */
    public function getRetentionPolicy()
    {
        return $this->_retentionPolicy;
    }
    
    /**
     * Sets retention policy
     * 
     * @param RetentionPolicy $policy object to use
     * 
     * @return none.
     */
    public function setRetentionPolicy($policy)
    {
        $this->_retentionPolicy = $policy;
    }
    
    /**
     * Gets write
     * 
     * @return bool.
     */
    public function getWrite()
    {
        return $this->_write;
    }
    
    /**
     * Sets write
     * 
     * @param bool $write new value.
     * 
     * @return none.
     */
    public function setWrite($write)
    {
        $this->_write = $write;
    }
            
    /**
     * Gets read
     * 
     * @return bool.
     */
    public function getRead()
    {
        return $this->_read;
    }
    
    /**
     * Sets read
     * 
     * @param bool $read new value.
     * 
     * @return none.
     */
    public function setRead($read)
    {
        $this->_read = $read;
    }
    
    /**
     * Gets delete
     * 
     * @return bool.
     */
    public function getDelete()
    {
        return $this->_delete;
    }
    
    /**
     * Sets delete
     * 
     * @param bool $delete new value.
     * 
     * @return none.
     */
    public function setDelete($delete)
    {
        $this->_delete = $delete;
    }
    
    /**
     * Gets version
     * 
     * @return string.
     */
    public function getVersion()
    {
        return $this->_version;
    }
    
    /**
     * Sets version
     * 
     * @param string $version new value.
     * 
     * @return none.
     */
    public function setVersion($version)
    {
        $this->_version = $version;
    }
    
    /**
     * Converts this object to array with XML tags
     * 
     * @return array. 
     */
    public function toArray()
    {
        return array(
            'Version'         => $this->_version,
            'Delete'          => Utilities::booleanToString($this->_delete),
            'Read'            => Utilities::booleanToString($this->_read),
            'Write'           => Utilities::booleanToString($this->_write),
            'RetentionPolicy' => !empty($this->_retentionPolicy)
                ? $this->_retentionPolicy->toArray()
                : null
        );
    }
}


