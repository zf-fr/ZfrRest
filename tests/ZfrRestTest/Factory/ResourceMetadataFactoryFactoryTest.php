<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace ZfrRestTest\Factory;

use PHPUnit_Framework_TestCase;
use Zend\ServiceManager\ServiceManager;
use ZfrRest\Factory\ResourceMetadataFactoryFactory;
use ZfrRest\Options\ModuleOptions;

/**
 * @licence MIT
 * @author  Michaël Gallego <mic.gallego@gmail.com>
 *
 * @group Coverage
 * @covers \ZfrRest\Factory\ResourceMetadataFactoryFactory
 */
class ResourceMetadataFactoryFactoryTest extends PHPUnit_Framework_TestCase
{
    public function driverConfigProvider()
    {
        return [
            [
                'config' => [
                    'class' => 'ZfrRest\Resource\Metadata\Driver\AnnotationDriver'
                ],
                'throwException' => false
            ],
            [
                'config' => [
                    'class' => 'InvalidDriver'
                ],
                'throwException' => true
            ]
        ];
    }

    /**
     * @dataProvider driverConfigProvider
     */
    public function testCreateFromFactory($driverConfig, $throwException)
    {
        if ($throwException) {
            $this->setExpectedException('ZfrRest\Exception\RuntimeException');
        }

        $serviceManager = new ServiceManager();
        $serviceManager->setService(
            'ZfrRest\Options\ModuleOptions',
            new ModuleOptions([
                'object_manager' => 'doctrine.entitymanager.orm_default',
                'drivers'        => [$driverConfig]
            ])
        );

        $metadataFactory = $this->getMock('Doctrine\Common\Persistence\Mapping\ClassMetadataFactory');

        $objectManager = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $objectManager->expects($this->once())->method('getMetadataFactory')->will($this->returnValue($metadataFactory));

        $serviceManager->setService('doctrine.entitymanager.orm_default', $objectManager);
        $serviceManager->setService('ZfrRest\Cache', $this->getMock('Metadata\Cache\CacheInterface'));

        $factory = new ResourceMetadataFactoryFactory();
        $result  = $factory->createService($serviceManager);

        $this->assertInstanceOf('Metadata\MetadataFactory', $result);
    }
}
