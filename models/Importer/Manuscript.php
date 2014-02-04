<?php

class Saga_Importer_Manuscript extends Saga_Importer_AbstractImporter
{

    public function import()
    {
        $elSets = array();
        $elSets['Dublin Core'] = array();
        $elSets['Dublin Core']['Title'] = $this->nodesToArray("//tei:teiHeader/tei:fileDesc/tei:titleStmt/tei:title");
        $elSets['Dublin Core']['License'] = $this->nodesToArray("//tei:teiHeader/tei:fileDesc/tei:publicationStmt", "publicationStmtToHtml" );

        $identifiers = array();
        $xmlId = $this->xpath->query("//tei:msDesc")->item(0)->getAttribute('xml:id');
        $identifiers[] = array('text' => $xmlId, 'html' => 0);
        $msId = $this->xpath->query("//tei:msIdentifier")->item(0);
        $identifiers[] = array('text' => $this->cleanTextContent($msId->textContent), 'html' => 0);

        $elSets['Dublin Core']['Identifier'] = $identifiers;

        $scripts = $this->nodesToArray("//tei:handNote", "handNoteToScriptValue");
        if($scripts) {
            $elSets['Item Type Metadata']['Script'] = $this->uniquifyElementTexts($scripts);
        }

        $scribes = $this->nodesToArray("//tei:handNote", "handNoteToScribeRef");
        if($scribes) {
            $elSets['Item Type Metadata']['Scribe'] = $this->uniquifyElementTexts($scribes);
        }

        $repo = $this->nodesToArray("//tei:repository");
        if($repo) {
            $elSets['Item Type Metadata']['Repository'] = $repo;
        }

        $binding = $this->nodesToArray("//tei:binding");
        if($binding) {
            $elSets['Item Type Metadata']['Binding'] = $binding;
        }

        $foliation = $this->nodesToArray("//tei:foliation");
        if($foliation) {
            $elSets['Item Type Metadata']['Foliation'] = $foliation;
        }

        $watermark = $this->nodesToArray("//tei:watermark");
        if($watermark) {
            $elSets['Item Type Metadata']['Watermark'] = $watermark;
        }

        $heightNode = $this->xpath->query("//tei:supportDesc/tei:support/tei:dimensions/tei:height")->item(0);
        if($heightNode) {
            $height = $heightNode->getAttribute('quantity') . ' ' . $heightNode->getAttribute('unit');
            $elSets['Item Type Metadata']['Height'] = array(array('text' => $height, 'html' => 0));
        }
        $widthNode = $this->xpath->query("//tei:supportDesc/tei:support/tei:dimensions/tei:width")->item(0);
        if($widthNode) {
            $width = $widthNode->getAttribute('quantity') . ' ' . $widthNode->getAttribute('unit');
            $elSets['Item Type Metadata']['Width'] = array(array('text' => $width, 'html' => 0));
        }
        $metadata = array('public' => 1, 'item_type_name' => "Manuscript");
        $item = insert_item($metadata, $elSets);

        $this->importSagas($item);
    }

    protected function importSagas($ms)
    {
        $msItems = $this->xpath->query("//tei:msItem");
        $elSets = array();
        foreach($msItems as $msItem) {
            //echo $msItem->getAttribute('n');

            $title = $this->nodesToArray("tei:title", null, $msItem);
            if($title) {
                $elSets['Dublin Core']['Title'] = $title;
            }

            $extent = $this->nodesToArray("tei:locus", "locusToExtent", $msItem);
            if($extent) {
                $elSets['Dublin Core']['Extent'] = $extent;
            }

            $incipit = $this->nodesToArray("tei:incipit", null, $msItem);
            if($incipit) {
                $elSets['Item Type Metadata']['Incipit'] = $incipit;
            }

            $note = $this->nodesToArray("tei:note", null, $msItem);
            if($note) {
                $elSets['Item Type Metadata']['Note'] = $note;
            }


            $metadata = array('public' => 1, 'item_type_name' => "Saga");
            $record = insert_item($metadata, $elSets);
            $map = new SagaMsMap;
            $map->ms_id = $ms->id;
            $map->saga_id = $record->id;
            $map->save();
            release_object($map);
            release_object($record);
        }
    }

    protected function locusToExtent($node)
    {
        $text = $node->getAttribute('from') . " to " . $node->getAttribute('to');
        return array('text' => $text, 'html' => 0);

    }

    protected function handNoteToScribeRef($node)
    {
        $scribeRef = trim($node->getAttribute('scribeRef'), '#');
        $scribeItem = get_db()->getTable('SagaAuthRef')->findItemFromXmlId($scribeRef);
        return array('text' => metadata($scribeItem, array('Dublin Core', 'Title')), 'html' => 0);
    }

    protected function handNoteToScriptValue($node)
    {
        $script = $node->getAttribute('script');
        $script = ucfirst($script);
        return array('text' => $script, 'html' => 0);
    }

    protected function publicationStmtToHtml($node)
    {
        $licence = $node->getElementsByTagName("licence")->item(0);
        $link = $licence->getAttribute('target');
        $html = "<a href='$link'>" . $this->cleanTextContent($licence->textContent) . "</a>";
        $a = array('html' => 1, 'text' => $html);
        return $a;
    }
}