<?php

namespace SeoAnalyzer\Metric\Page;

use SeoAnalyzer\Metric\AbstractMetric;

class SchemaMetric extends AbstractMetric
{
    public $description = 'Schema on page';

    /**
     * @inheritdoc
     */
    public function analyze(): string
    {
        if (empty($this->value)) {
            $this->impact = 3;
            return 'There is nothing to do here as there is no schema on the page.';
        }

        $this->impact = 0;
       
        return "schema found on the page";
    }
}
