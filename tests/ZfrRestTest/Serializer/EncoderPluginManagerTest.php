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

namespace ZfrRestTest\Serializer;

use PHPUnit_Framework_TestCase as TestCase;
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfig;
use ZfrRest\Serializer\EncoderPluginManager;

class EncoderPluginManagerTest extends TestCase
{
    /**
     * @var EncoderPluginManager
     */
    protected $encoderPluginManager;

    public function setUp()
    {
        parent::setUp();

        $this->encoderPluginManager = new EncoderPluginManager();
    }

    public function testCanRetrieveEncodersFromDefaultFormat()
    {
        $plugin = $this->encoderPluginManager->get('json');
        $this->assertInstanceOf('Symfony\Component\Serializer\Encoder\JsonEncoder', $plugin);
        $this->assertInstanceOf('Symfony\Component\Serializer\Encoder\EncoderInterface', $plugin);
        $this->assertInstanceOf('Symfony\Component\Serializer\Encoder\DecoderInterface', $plugin);

        $plugin = $this->encoderPluginManager->get('xml');
        $this->assertInstanceOf('Symfony\Component\Serializer\Encoder\XmlEncoder', $plugin);
        $this->assertInstanceOf('Symfony\Component\Serializer\Encoder\EncoderInterface', $plugin);
        $this->assertInstanceOf('Symfony\Component\Serializer\Encoder\DecoderInterface', $plugin);
    }

    public function testCanRetrievePluginManagerWithServiceManager()
    {
        $serviceManager = new ServiceManager(
            new ServiceManagerConfig(array(
                'factories' => array(
                    'EncoderPluginManager' => 'ZfrRest\Mvc\Service\EncoderPluginManagerFactory',
                ),
            ))
        );
        $serviceManager->setService('Config', array());

        $encoderPluginManager = $serviceManager->get('EncoderPluginManager');
        $this->assertInstanceOf('ZfrRest\Serializer\EncoderPluginManager', $encoderPluginManager);
    }
}
