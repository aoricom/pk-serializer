<?php

namespace JMS\Serializer;

use Context\DirectBundle\Platform\Communication\Serialization\Nillable;
use Doctrine\Common\Annotations\AnnotationReader;
use JMS\Serializer\Accessor\AccessorStrategyInterface;
use JMS\Serializer\Metadata\PropertyMetadata;
use JMS\Serializer\Naming\PropertyNamingStrategyInterface;

final class DirectJsonSerializationVisitor extends JsonSerializationVisitor
{
    /**
     * @var AnnotationReader
     */
    private $reader;

    /**
     * @param PropertyNamingStrategyInterface $namingStrategy
     * @param AnnotationReader                $reader
     * @param null|AccessorStrategyInterface  $accessorStrategy
     */
    public function __construct(
        PropertyNamingStrategyInterface $namingStrategy,
        AnnotationReader $reader,
        AccessorStrategyInterface $accessorStrategy = null
    ) {
        parent::__construct($namingStrategy, $accessorStrategy);
        $this->reader = $reader;
    }

    /**
     * @param array   $data
     * @param array   $type
     * @param Context $context
     *
     * @return array|\ArrayObject|mixed
     */
    public function visitArray($data, array $type, Context $context)
    {
        return parent::visitArray($data, $type, $context);
    }

    /**
     * @param PropertyMetadata $metadata
     * @param mixed            $data
     * @param Context          $context
     *
     * @return void
     */
    public function visitProperty(PropertyMetadata $metadata, $data, Context $context)
    {
        // todo обобщить
        if (
            $context->hasAttribute('direct_request_method') &&
            $context->getAttribute('direct_request_method') === 'add'
        ) {
            parent::visitProperty($metadata, $data, $context);

            return;
        }

        if ($this->annotationPresent($metadata->reflection)) {
            $currentShouldSerializeNull = $context->shouldSerializeNull();
            $context->setSerializeNull(true);
            parent::visitProperty($metadata, $data, $context);
            $context->setSerializeNull($currentShouldSerializeNull);
        } else {
            parent::visitProperty($metadata, $data, $context);
        }
    }

    /**
     * @param \ReflectionProperty $property
     *
     * @return bool
     */
    private function annotationPresent(\ReflectionProperty $property): bool
    {
        return (bool) $this->reader->getPropertyAnnotation($property, Nillable::class);
    }
}
