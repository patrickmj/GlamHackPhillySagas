<?php

class Table_SagaAuthRef extends Omeka_Db_Table
{
    public function findByXmlId($id)
    {
        $select = $this->getSelect();
        $select->where("xml_id = ?", $id);
        return $this->fetchObject($select);
    }

    public function findItemFromXmlId($id)
    {
        $db = $this->getDb();
        $itemTable = $this->getDb()->getTable("Item");
        $select = $itemTable->getSelect();
        $itemAlias = $itemTable->getTableAlias();
        $sagaAlias = $this->getTableAlias();
        $select->join(array($sagaAlias => $db->SagaAuthRef ), "$sagaAlias.item_id = $itemAlias.id", array());
        return $itemTable->fetchObject($select);
    }
}