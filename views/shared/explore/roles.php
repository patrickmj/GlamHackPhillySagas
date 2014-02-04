<?php
 echo head(array('title' => 'roles'));

 ?>

 <?php
 function saga_role_search_link($text)
 {
        $url = url('items/browse', array(
            'advanced' => array(
                array(
                    'element_id' => 108,
                    'type' => 'is exactly',
                    'terms' =>$text,
                )
            )
        ));
        return "<a href=\"$url\">$text</a>";

 }
 ?>

 <?php echo count($roles); ?>

 <ul>

 <?php foreach($roles as $role): ?>
 <li>
 <?php echo saga_role_search_link($role->text); ?>
 </li>

 <?php endforeach; ?>
 </ul>

 <?php
 echo foot();
 ?>