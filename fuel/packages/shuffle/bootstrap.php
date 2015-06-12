<?php
use Fuel\Core\Autoloader;
Autoloader::add_classes(array(
   'Shuffle\\DataReader'  => __DIR__.'/classes/data_reader.php',
    'Shuffle\\DataEditor' => __DIR__.'/classes/data_editor.php',
    'Shuffle\\DataWriter' => __DIR__.'/classes/data_writer.php',
    'Shuffle\\ShuffleFunction' => __DIR__.'/classes/shuffle_function.php',
    'Shuffle\\Evaluator' => __DIR__.'/classes/evaluator.php',
    'Shuffle\\RelationsScore' => __DIR__.'/classes/relations_score.php',
    'Shuffle\\Group' => __DIR__.'/classes/group.php',
));