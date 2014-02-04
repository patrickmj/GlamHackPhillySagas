<?php

class SagaPlugin extends Omeka_Plugin_AbstractPlugin
{

    protected $_hooks = array(
            'public_items_show'
            );

    protected $_filters = array();

    public function hookInstall()
    {

    }

    public function hookPublicItemsShow($args)
    {
        $item = $args['item'];
        $view = $args['view'];
        if(metadata($item, 'Item Type Name') == 'Manuscript') {
            $sagas = get_db()->getTable('SagaMsMap')->findSagasForMs($item->id);
            $view->sagas = $sagas;
        }
    }
}

?>