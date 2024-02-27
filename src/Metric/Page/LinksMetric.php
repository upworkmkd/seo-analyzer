<?php

namespace SeoAnalyzer\Metric\Page;

use SeoAnalyzer\Metric\AbstractMetric;

class LinksMetric extends AbstractMetric
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
        $linksCount = count($this->value);
        
        return "There are {$linksCount} links on this page";
    }
}
