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

namespace ZfrRestTest\Resource\Metadata\Driver;

use PHPUnit_Framework_TestCase as TestCase;
use ZfrRest\Resource\Metadata\ResourceMetadataInterface;
use ZfrRestTest\Util\ServiceManagerFactory;

class AnnotationDriverTest extends TestCase
{
    public function testExtractAnnotation()
    {
        $serviceManager          = ServiceManagerFactory::getServiceManager();
        $resourceMetadataFactory = $serviceManager->get('ZfrRest\Resource\Metadata\MetadataFactory');

        /** @var \ZfrRest\Resource\Metadata\ResourceMetadataInterface $resourceMetadata */
        $resourceMetadata = $resourceMetadataFactory->getMetadataForClass('ZfrRestTest\Asset\Annotation\User')->getRootClassMetadata();

        $this->assertEquals('ZfrRestTest\Asset\Annotation\User', $resourceMetadata->getClassName());
        $this->assertEquals('Application\Controller\UserController', $resourceMetadata->getControllerName());
        $this->assertSame('Application\InputFilter\UserInputFilter', $resourceMetadata->getInputFilterName());
        $this->assertSame('Application\Hydrator\UserHydrator', $resourceMetadata->getHydratorName());
        $this->assertFalse($resourceMetadata->allowTraversal());

        $collectionMetadata = $resourceMetadata->getCollectionMetadata();
        $this->assertInstanceOf('ZfrRest\Resource\Metadata\CollectionResourceMetadataInterface', $collectionMetadata);
        $this->assertEquals('Application\Controller\UserListController', $collectionMetadata->getControllerName());
        $this->assertSame('Application\Hydrator\UserHydrator', $collectionMetadata->getHydratorName());

        // Should reuse the input filter from Resource annotation as none is defined at collection level
        $this->assertSame('Application\InputFilter\UserInputFilter', $collectionMetadata->getInputFilterName());

        $this->assertTrue($resourceMetadata->hasAssociation('tweets'));
        $this->assertFalse($resourceMetadata->hasAssociation('posts'));

        $tweetMetadata = $resourceMetadata->getAssociationMetadata('tweets');

        $this->assertInstanceOf('ZfrRest\Resource\Metadata\ResourceMetadataInterface', $tweetMetadata);
        $this->assertEquals('ZfrRestTest\Asset\Annotation\Tweet', $tweetMetadata->getClassName());
        $this->assertEquals('Application\Controller\TweetController', $tweetMetadata->getControllerName());
        $this->assertEquals('Application\InputFilter\TweetInputFilter', $tweetMetadata->getInputFilterName());
        $this->assertEquals('DoctrineModule\Stdlib\Hydrator\DoctrineObject', $tweetMetadata->getHydratorName());
        $this->assertTrue($tweetMetadata->allowTraversal());
        $this->assertEquals(ResourceMetadataInterface::SERIALIZATION_STRATEGY_LOAD, $tweetMetadata->getSerializationStrategy());

        // Note that this one has been overriden by the User class at the association level
        $this->assertEquals('Application\Controller\UserTweetListController', $tweetMetadata->getCollectionMetadata()->getControllerName());
    }
}
