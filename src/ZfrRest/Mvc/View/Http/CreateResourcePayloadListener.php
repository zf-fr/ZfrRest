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

namespace ZfrRest\Mvc\View\Http;

use Traversable;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\Hydrator\HydratorPluginManager;
use Zend\Stdlib\ResponseInterface;
use Zend\View\Model\ModelInterface;
use ZfrRest\Resource\Metadata\ResourceMetadataInterface;
use ZfrRest\Resource\Resource;

/**
 * CreateResourceRepresentationListener. This listener is used to extract data from a resource
 *
 * @license MIT
 * @author  Michaël Gallego <mic.gallego@gmail.com>
 */
class CreateResourcePayloadListener extends AbstractListenerAggregate
{
    /**
     * @var HydratorPluginManager
     */
    protected $hydratorPluginManager;

    /**
     * @param HydratorPluginManager $hydratorPluginManager
     */
    public function __construct(HydratorPluginManager $hydratorPluginManager)
    {
        $this->hydratorPluginManager = $hydratorPluginManager;
    }

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $sharedManager = $events->getSharedManager();
        $sharedManager->attach('Zend\Stdlib\DispatchableInterface', MvcEvent::EVENT_DISPATCH, array($this, 'createPayload'), -40);
    }

    /**
     * The logic is as follow: extract the resource metadata, use the bound hydrator to extract data, and set the
     * data as new result
     *
     * @param  MvcEvent $e
     * @return void
     */
    public function createPayload(MvcEvent $e)
    {
        $result = $e->getResult();
        if ($result instanceof ModelInterface || $result instanceof ResponseInterface || empty($result)) {
            return;
        }

        /** @var \ZfrRest\Resource\ResourceInterface $resource */
        $resource = $e->getRouteMatch()->getParam('resource');
        $hydrator = $this->hydratorPluginManager->get('ZfrRest\Stdlib\Hydrator\RestAggregateHydrator');

        // In some cases, for instance in POST methods, the resource is a collection, while what is returned from
        // a controller is, most of the time, a single item of the collection. The "resource" is therefore changed
        // semantically, that's why we need to create a new resource (although most of the time it's not needed)
        if ($resource->getData() !== $result) {
            $resource = new Resource($result, $resource->getMetadata());
        }

        $e->setResult($hydrator->extract($resource));
    }
}
