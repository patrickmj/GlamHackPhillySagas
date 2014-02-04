<?php

class Table_SagaMsMap extends Omeka_Db_Table
{
    public function findSagasForMs($ms)
    {
        $db = $this->getDb();
        $itemTable = $db->getTable('Item');
        $select = $itemTable->getSelect();
        $alias = $this->getTableAlias();
        $select->join(array($alias => $db->SagaMsMap), "$alias.saga_id = items.id", array() );
        $select->where("$alias.ms_id = ?", $ms);
        return $itemTable->fetchObjects($select);
    }
}