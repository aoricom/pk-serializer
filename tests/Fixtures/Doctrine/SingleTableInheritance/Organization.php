<?php

declare(strict_types=1);

namespace JMS\Serializer\Tests\Fixtures\Doctrine\SingleTableInheritance;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *     "school" = "JMS\Serializer\Tests\Fixtures\Doctrine\SingleTableInheritance\School"
 * })
 */
abstract class Organization
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
}
