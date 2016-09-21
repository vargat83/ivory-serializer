<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Serializer\Visitor\Json;

use Ivory\Serializer\Accessor\AccessorInterface;
use Ivory\Serializer\Context\ContextInterface;
use Ivory\Serializer\Exclusion\ExclusionStrategyInterface;
use Ivory\Serializer\Mapping\TypeMetadataInterface;
use Ivory\Serializer\Naming\NamingStrategyInterface;
use Ivory\Serializer\Visitor\AbstractSerializationVisitor;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class JsonSerializationVisitor extends AbstractSerializationVisitor
{
    /**
     * @var int
     */
    private $options;

    /**
     * @param AccessorInterface               $accessor
     * @param ExclusionStrategyInterface|null $exclusionStrategy
     * @param NamingStrategyInterface|null    $namingStrategy
     * @param int                             $options
     */
    public function __construct(
        AccessorInterface $accessor,
        ExclusionStrategyInterface $exclusionStrategy = null,
        NamingStrategyInterface $namingStrategy = null,
        $options = 0
    ) {
        parent::__construct($accessor, $exclusionStrategy, $namingStrategy);

        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    protected function doVisitArray($data, TypeMetadataInterface $type, ContextInterface $context)
    {
        if ($data === [] && class_exists($type->getName())) {
            return $this->visitData((object) $data, $type, $context);
        }

        return parent::doVisitArray($data, $type, $context);
    }

    /**
     * {@inheritdoc}
     */
    protected function encode($data)
    {
        $result = @json_encode($data, $this->options);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException(json_last_error_msg());
        }

        return $result;
    }
}
