<?php

namespace SeoAnalyzer\Metric\Page;

use SeoAnalyzer\Metric\AbstractMetric;

class ImagesMetric extends AbstractMetric
{
    public $description = 'Images on the page';

    /**
     * @inheritdoc
     */
    public function analyze(): string
    {
        if (empty($this->value)) {
            return 'There is nothing to do here as there is no images on the site.';
        }
        $imagesCount = count($this->value);
        
        return "There are {$imagesCount} images on this page";
    }
}
