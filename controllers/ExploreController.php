<?php

class Saga_ExploreController extends Omeka_Controller_AbstractActionController
{
    public function occupationsAction()
    {
        $elTextTable = $this->_helper->db->getTable('ElementText');
        $select = $elTextTable->getSelect();
        $select->distinct();
        $sql = "
        SELECT DISTINCT `text`
FROM `omeka_element_texts`
WHERE `element_id` = 34
ORDER BY `text` ASC
        ";
        $occupations = $elTextTable->fetchObjects($sql);
        $this->view->occupations = $occupations;

    }

    public function rolesAction()
    {
        $elTextTable = $this->_helper->db->getTable('ElementText');
        $select = $elTextTable->getSelect();
        $select->distinct();
        $sql = "
        SELECT DISTINCT `text`
FROM `omeka_element_texts`
WHERE `element_id` = 108
ORDER BY `text` ASC
        ";
        $roles = $elTextTable->fetchObjects($sql);
        $this->view->roles = $roles;


    }
}