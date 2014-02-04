<?php
 echo head(array('title' => 'Occupations'));

 ?>

 <?php
 function saga_occupation_search_link($text)
 {
        $url = url('items/browse', array(
            'advanced' => array(
                array(
                    'element_id' => 34,
                    'type' => 'is exactly',
                    'terms' =>$text,
                )
            )
        ));
        return "<a href=\"$url\">$text</a>";

 }
 ?>

 <?php echo count($occupations); ?>

 <ul>

 <?php foreach($occupations as $occupation): ?>
 <li>
 <?php echo saga_occupation_search_link($occupation->text); ?>
 </li>

 <?php endforeach; ?>
 </ul>

 <?php
 echo foot();
 ?>