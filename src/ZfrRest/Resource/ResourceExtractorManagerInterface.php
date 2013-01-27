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

namespace ZfrRest\Resource;

/**
 * @license MIT
 * @author  Marco Pivetta <ocramius@gmail.com>
 */
interface ResourceExtractorManagerInterface
{
    /**
     * Retrieves a resource extractor for the provided resource
     *
     * @param string $resourceName
     * @param mixed  $resource
     *
     * @return \ZfrRest\Resource\ResourceExtractorInterface
     *
     * @throws \ZfrRest\Resource\Exception\UnknownResourceException
     */
    public function getResourceExtractor($resourceName, $resource);

    /**
     * Retrieves a resource extractor for the provided association
     *
     * @param string $resourceName
     * @param string $associationName
     * @param mixed  $resource
     *
     * @return \ZfrRest\Resource\ResourceExtractorInterface
     *
     * @throws \ZfrRest\Resource\Exception\UnknownResourceException
     */
    public function getResourceAssociationExtractor($resourceName, $associationName, $resource);
}
