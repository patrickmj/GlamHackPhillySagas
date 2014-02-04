<?php

class Saga_Importer_Person extends Saga_Importer_AbstractImporter
{
    public function import()
    {
        $persons = $this->dom->getElementsByTagName("person");
        foreach($persons as $person) {
            $this->importPerson($person);
        }
    }

    public function importPerson($node)
    {
        $elSets = array();
        $elSets['Dublin Core']['Title'] = $this->personTitle($node);

        $identifier = $node->getAttribute("xml:id");
        $elSets['Dublin Core']['Identifier'] = array( array('text' => $identifier, 'html' => false) );

        if($death = $this->death($node)) {
            $elSets['Item Type Metadata']['Death Date'] = $death;
        }
        if($birth = $this->birth($node)) {
            $elSets['Item Type Metadata']['Birth Date'] = $birth;
        }

        if($occupation = $this->nodesToArray("tei:occupation", null, $node) ) {
            $elSets['Item Type Metadata']['Occupation'] = $occupation;
        }

        if($roles = $this->role($node)) {
            $roles = $this->uniquifyElementTexts($roles);
            $elSets['Item Type Metadata']['Role'] = $roles;
        }
        $metadata = array('public' => 1, 'item_type_name' => 'Person');
        $item = insert_item($metadata, $elSets);
        $count = get_db()->getTable('SagaAuthRef')->count(array('item_id' => $item->id));
        if($count == 0) {
            $authRef = new SagaAuthRef;
            $authRef->item_id = $item->id;
            $authRef->xml_id = $identifier;
            $authRef->save();
        }


    }

    protected function role($node)
    {
        $role = $node->getAttribute('role');
        if(!$role) {
            return false;
        }
        $allRoles = explode(' ', $role);
        $roleArray = array();
        $roleArray[] = array('text' => ucfirst($role), 'html' => false);
        foreach($allRoles as $splitRole) {
            $roleArray[] = array('text' => ucfirst($splitRole), 'html' => false);
        }
        return $roleArray;
    }

    protected function death($node)
    {
        $deathNode = $node->getElementsByTagName("death")->item(0);
        $deathArray = array();
        if(!$deathNode) {
            return false;
        }
        $deathArray[] = array('text' => $deathNode->getAttribute('when'), 'html' => false);
        $deathArray[] = array('text' => $this->cleanTextContent($deathNode->textContent), 'html' => false);
        return $deathArray;
    }


    protected function birth($node)
    {
        $birthNode = $node->getElementsByTagName("birth")->item(0);
        $birthArray = array();
        if(!$birthNode) {
            return false;
        }
        $birthArray[] = array('text' => $birthNode->getAttribute('when'), 'html' => false);
        $birthArray[] = array('text' => $this->cleanTextContent($birthNode->textContent), 'html' => false);
        return $birthArray;
    }

    protected function personTitle($node)
    {
        $a = array();
        $name = $node->getElementsByTagName("persName")->item(0)->textContent;
        $name = $this->cleanTextContent($name);
        $a[] = array('text' => $name, 'html' => false);
        return $a;
    }
}