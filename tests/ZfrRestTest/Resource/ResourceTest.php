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

namespace ZfrRestTest\Resource;

use PHPUnit_Framework_TestCase as TestCase;
use ReflectionClass;
use ZfrRest\Resource\Resource;

/**
 * Tests for {@see \ZfrRest\Resource\Resource}
 *
 * @author Marco Pivetta <ocramius@gmail.com>
 */
class ResourceTest extends TestCase
{
    /**
     * @covers \ZfrRest\Resource\Resource::__construct
     * @covers \ZfrRest\Resource\Resource::getData
     * @covers \ZfrRest\Resource\Resource::getMetadata
     * @covers \ZfrRest\Resource\Resource::isCollection
     *
     * @dataProvider collectionResourceProvider
     *
     * @param string $className
     * @param mixed  $instance
     * @param bool   $isCollection
     */
    public function testResource($className, $instance, $isCollection)
    {
        $metadata = $this->createMetadata(new ReflectionClass($className));
        $resource = new Resource($instance, $metadata);

        $this->assertSame($instance, $resource->getData());
        $this->assertSame($metadata, $resource->getMetadata());
        $this->assertSame($isCollection, $resource->isCollection());
    }

    /**
     * @covers \ZfrRest\Resource\Resource::__construct
     * @covers \ZfrRest\Resource\Exception\InvalidResourceException::invalidResourceProvided
     */
    public function testDisallowsInvalidResource()
    {
        $metadata = $this->createMetadata(new ReflectionClass($this));

        $this->setExpectedException('ZfrRest\\Resource\\Exception\\InvalidResourceException');

        new Resource(new \stdClass(), $metadata);
    }

    /**
     * Data provider for various collection types
     *
     * @return array
     */
    public function collectionResourceProvider()
    {
        return array(
            array('stdClass', $this->getMock('Iterator'), true),
            array('stdClass', $this->getMock('Doctrine\\Common\\Collections\\Selectable'), true),
            array('stdClass', $this->getMock('Doctrine\\Common\\Collections\\Collection'), true),
            array('stdClass', array(), true),
            array('stdClass', new \stdClass(), false),
        );
    }

    /**
     * @param ReflectionClass $reflectionClass
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\ZfrRest\Resource\Metadata\ResourceMetadataInterface
     */
    private function createMetadata(ReflectionClass $reflectionClass)
    {
        $resourceMetadata = $this->getMock('ZfrRest\\Resource\\Metadata\\ResourceMetadataInterface');
        $metadata         = $this->getMock('Doctrine\\Common\\Persistence\\Mapping\\ClassMetadata');

        $resourceMetadata->expects($this->any())->method('getClassMetadata')->will($this->returnValue($metadata));
        $metadata->expects($this->any())->method('getReflectionClass')->will($this->returnValue($reflectionClass));

        return $resourceMetadata;
    }
}
