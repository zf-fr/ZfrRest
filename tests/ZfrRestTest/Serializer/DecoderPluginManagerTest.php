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
use ZfrRest\Serializer\DecoderPluginManager;
use ZfrRestTest\Util\ServiceManagerFactory;

class DecoderPluginManagerTest extends TestCase
{
    /**
     * @var DecoderPluginManager
     */
    protected $decoderPluginManager;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    public function setUp()
    {
        $this->serviceManager       = ServiceManagerFactory::getServiceManager();
        $this->decoderPluginManager = new DecoderPluginManager();
    }

    public function testCanRetrieveEncodersFromDefaultFormat()
    {
        $plugin = $this->decoderPluginManager->get('application/json');
        $this->assertInstanceOf('Symfony\Component\Serializer\Encoder\JsonDecode', $plugin);
        $this->assertInstanceOf('Symfony\Component\Serializer\Encoder\DecoderInterface', $plugin);

        $plugin = $this->decoderPluginManager->get('application/xml');
        $this->assertInstanceOf('Symfony\Component\Serializer\Encoder\XmlEncoder', $plugin);
        $this->assertInstanceOf('Symfony\Component\Serializer\Encoder\DecoderInterface', $plugin);
    }

    public function testCanRetrievePluginManagerWithServiceManager()
    {
        $decoderPluginManager = $this->serviceManager->get('ZfrRest\Serializer\DecoderPluginManager');
        $this->assertInstanceOf('ZfrRest\Serializer\DecoderPluginManager', $decoderPluginManager);
    }
}
