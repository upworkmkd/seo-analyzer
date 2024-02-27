<?php

namespace SeoAnalyzer\Metric\Page;

use SeoAnalyzer\Metric\AbstractMetric;

class CanonicalMetric extends AbstractMetric
{
    public $description = 'Links on the page';

    /**
     * @inheritdoc
     */
    public function analyze(): string
    {
        if (empty($this->value)) {
            return 'There is nothing to do here as there is no links on the site.';
        }
        
        return "";
    }
}
