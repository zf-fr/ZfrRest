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

namespace ZfrRest\Mvc\Controller;

use Doctrine\Common\Collections\Collection;
use Zend\Http\Request as HttpRequest;
use Zend\Mvc\Controller\AbstractController;
use Zend\Mvc\MvcEvent;
use Zend\Paginator\Paginator;
use Zend\Stdlib\RequestInterface;
use Zend\Stdlib\ResponseInterface;
use ZfrRest\Http\Exception\Client\NotFoundException;
use ZfrRest\Mvc\Controller\MethodHandler\MethodHandlerPluginManager;
use ZfrRest\Mvc\Exception\RuntimeException;
use ZfrRest\Resource\Metadata\ResourceMetadataInterface;
use ZfrRest\Resource\ResourceInterface;
use ZfrRest\View\Model\ResourceModel;

/**
 * @author  Michaël Gallego <mic.gallego@gmail.com>
 * @licence MIT
 *
 * @method Paginator paginatorWrapper(Collection $data, $criteria = [])
 * @method ResourceModel resourceModel($data, ResourceMetadataInterface $metadata = null)
 */
class AbstractRestfulController extends AbstractController
{
    /**
     * @var MethodHandlerPluginManager
     */
    protected $methodHandlerManager;

    /**
     * {@inheritDoc}
     */
    public function dispatch(RequestInterface $request, ResponseInterface $response = null)
    {
        if (!$request instanceof HttpRequest) {
            throw new RuntimeException(sprintf(
                'ZfrRest only works with HTTP requests, "%s" given',
                get_class($request)
            ));
        }

        return parent::dispatch($request, $response);
    }

    /**
     * {@inheritDoc}
     */
    public function onDispatch(MvcEvent $event)
    {
        /* @var HttpRequest $request */
        $request = $this->getRequest();
        $handler = $this->getMethodHandlerManager()->get($request->getMethod());

        // We should always have a resource, otherwise throw an 404 exception
        if (!$resource = $this->getMatchedResource()) {
            throw new NotFoundException();
        }

        $result = $handler->handleMethod($this, $resource);
        $event->setResult($result);

        return $result;
    }

    /**
     * @return ResourceInterface|null
     */
    public function getMatchedResource()
    {
        return $this->getEvent()->getRouteMatch()->getParam('resource', null);
    }

    /**
     * @return ResourceInterface|null
     */
    public function getContextResource()
    {
        return $this->getEvent()->getRouteMatch()->getParam('context', null);
    }

    /**
     * Get the method handler plugin manager
     *
     * @return MethodHandlerPluginManager
     */
    public function getMethodHandlerManager()
    {
        if (null === $this->methodHandlerManager) {
            $this->methodHandlerManager = $this->serviceLocator->get(
                'ZfrRest\Mvc\Controller\MethodHandler\MethodHandlerPluginManager'
            );
        }

        return $this->methodHandlerManager;
    }
}
