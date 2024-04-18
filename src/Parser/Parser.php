<?php

namespace SeoAnalyzer\Parser;

class Parser extends AbstractParser
{
    /**
     * @inheritDoc
     */
    public function getMeta(): array
    {
        $meta = [];
        foreach ($this->getDomElements('meta') as $item) {
            if ($item->getAttribute('name')) {
                $meta[$item->getAttribute('name')] = trim($item->getAttribute('content'));
            }
            if ($item->getAttribute('property')) {
                $meta[$item->getAttribute('property')] = trim($item->getAttribute('content'));
            }
        }
        return $meta;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        $headers = [];
        $dom = new \DOMDocument();
        @$dom->loadHTML($this->html);
        $xpath = new \DOMXPath($dom);
        $Htmlheaders = $xpath->query('//h1 | //h2 | //h3 | //h4 | //h5 | //h6');

        if ($Htmlheaders->length > 0) {
            foreach ($Htmlheaders as $Htmlheader) {
                $textContent = trim($Htmlheader->textContent);
                $tagName = trim($Htmlheader->tagName);
                // Check if the script contains schema information
                if ($textContent) {
                    $headers[] = ['tag' => $tagName, 'text' => $textContent];
                }
            }
        }

        return $headers;
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        if ($this->getDomElements('title')->length > 0) {
            return trim($this->getDomElements('title')->item(0)->nodeValue);
        }
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getAlts(): array
    {
        $alts = [];
        if ($this->getDomElements('img')->length > 0) {
            foreach ($this->getDomElements('img') as $img) {
                $alts[] = trim($img->getAttribute('alt'));
            }
        }
        return $alts;
    }

    public function getImages(): array
    {
        $images = [];
        if ($this->getDomElements('img')->length > 0) {
            foreach ($this->getDomElements('img') as $img) {
                if ($img->getAttribute('src')) {
                    // Get image size in bytes
                    $src = trim($img->getAttribute('src'));
                    $alt = trim($img->getAttribute('alt'));
                    $title = trim($img->getAttribute('title'));
                    $sizeInBytes = 0;
                    $headers = get_headers($src, 1);
                    if (isset($headers['Content-Length'])) {
                        $sizeInBytes = is_array($headers['Content-Length']) ? end($headers['Content-Length']) : $headers['Content-Length'];
                    } else {
                        $sizeInBytes = 0;
                    }
                    $sizeInKB = round($sizeInBytes / 1024, 2); // Con
                    $images[] = [
                        'src' => $src,
                        'alt' => $alt,
                        'title' => $title,
                        "size" => $sizeInKB
                    ];
                }

            }
        }
        return $images;
    }

    public function getSchema(): array
    {
        $schema = [];
        $dom = new \DOMDocument();
        @$dom->loadHTML($this->html);
        $xpath = new \DOMXPath($dom);
        $query = "//script[@type='application/ld+json']";
        $scriptTags = $xpath->query($query);
        if ($scriptTags->length > 0) {
            foreach ($scriptTags as $script) {
                $textContent = trim($script->textContent);

                // Check if the script contains schema information
                if ($textContent) {
                    $schema[] = $textContent;
                }
            }
        }

        return $schema;
    }
    public function getCanonical(): string
    {

        foreach ($this->getDomElements('link') as $item) {

            if ($item->getAttribute('rel') == 'canonical') {
                return $item->getAttribute('href');
            }
        }

        return "";
    }

    public function getLinks(): array
    {
        $links = [];
        if ($this->getDomElements('a')->length > 0) {
            foreach ($this->getDomElements('a') as $link) {
                if ($link->getAttribute('href')) {
                    $links[] = [
                        'href' => trim($link->getAttribute('href')),
                        'target' => trim($link->getAttribute('target')),
                        'text' => trim($link->textContent)
                    ];
                }

            }
        }
        return $links;
    }
    /**
     * @inheritDoc
     */
    public function getText(): string
    {
        $this->removeTags('script');
        $this->removeTags('style');
        $text = strip_tags($this->dom->saveHTML());
        return preg_replace('!\s+!', ' ', strip_tags($text));
    }
}
