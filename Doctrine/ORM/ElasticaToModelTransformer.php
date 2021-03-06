<?php

namespace Fazland\ElasticaBundle\Doctrine\ORM;

use Fazland\ElasticaBundle\Doctrine\AbstractElasticaToModelTransformer;
use Doctrine\ORM\Query;

/**
 * Maps Elastica documents with Doctrine objects
 * This mapper assumes an exact match between
 * elastica documents ids and doctrine object ids.
 */
class ElasticaToModelTransformer extends AbstractElasticaToModelTransformer
{
    const ENTITY_ALIAS = 'o';

    /**
     * Fetch objects for theses identifier values.
     *
     * @param array   $identifierValues ids values
     * @param Boolean $hydrate          whether or not to hydrate the objects, false returns arrays
     *
     * @return array of objects or arrays
     */
    protected function findByIdentifiers(array $identifierValues, $hydrate)
    {
        if (empty($identifierValues)) {
            return array();
        }
        $hydrationMode = $hydrate ? Query::HYDRATE_OBJECT : Query::HYDRATE_ARRAY;

        $qb = $this->getEntityQueryBuilder();
        $qb->andWhere($qb->expr()->in(static::ENTITY_ALIAS.'.'.$this->options['identifier'], ':values'))
            ->setParameter('values', $identifierValues);

        $query = $qb->getQuery();

        foreach ($this->options['hints'] as $hint) {
            $query->setHint($hint['name'], $hint['value']);
        }

        return $query->setHydrationMode($hydrationMode)->execute();
    }

    /**
     * Retrieves a query builder to be used for querying by identifiers.
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getEntityQueryBuilder()
    {
        $repository = $this->registry
            ->getManagerForClass($this->objectClass)
            ->getRepository($this->objectClass);

        return $repository->{$this->options['query_builder_method']}(static::ENTITY_ALIAS);
    }
}
