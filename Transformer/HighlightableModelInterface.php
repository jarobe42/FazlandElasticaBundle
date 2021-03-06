<?php

namespace Fazland\ElasticaBundle\Transformer;

/**
 * Indicates that the model should have elastica highlights injected.
 */
interface HighlightableModelInterface
{
    /**
     * Returns a unique identifier for the model.
     *
     * @return mixed
     */
    public function getId();

    /**
     * Set ElasticSearch highlight data.
     *
     * @param array $highlights array of highlight strings
     */
    public function setElasticHighlights(array $highlights);
}
