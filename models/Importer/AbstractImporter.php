<?php


abstract class Saga_Importer_AbstractImporter
{
    protected $dom;
    protected $xpath;

    abstract function import();

    public function loadTei($path)
    {
        if(! $this->dom) {
            $this->dom = new DOMDocument;
        }
        $this->dom->load($path);
        $this->xpath = new DOMXPath($this->dom);
        $this->xpath->registerNamespace('tei', "http://www.tei-c.org/ns/1.0");

    }

    protected function nodesToArray($xpath, $callback = null, $context = null)
    {
        if($context) {
            $nodes = $this->xpath->query($xpath, $context);
        } else {
            $nodes = $this->xpath->query($xpath);
        }

        if(empty($nodes)) {
            return false;
        }
        $nodesArray = array();
        foreach($nodes as $node) {
            $nodesArray[] = $this->nodeToElementTextValue($node, $callback);
        }
        return $nodesArray;
    }

    protected function nodeToElementTextValue($node, $transformCallback = null)
    {
        if($transformCallback) {
            $textArray = call_user_func(array($this, $transformCallback), $node);
        } else {
            $value = $this->cleanTextContent($node->textContent);
            $textArray = array('text' => $value, 'html' => 0);
        }
        return $textArray;
    }

    protected function uniquifyElementTexts($elTextArray)
    {
        $uniqued = array();
        $usedValues = array();
        foreach($elTextArray as $index => $valArray) {
            if(in_array($valArray['text'], $usedValues)) {
                unset($elTextArray[$index]);
            } else {
                $usedValues[] = $valArray['text'];
            }
        }
        return $elTextArray;
    }

    protected function cleanTextContent($string)
    {
        return trim( preg_replace( '/\s+/', ' ', $string ) );
    }

}