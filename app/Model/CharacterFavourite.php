<?php 
// app/Model/User.php
App::uses('AppModel', 'Model');

class CharacterFavourite extends AppModel {
 
    var $belongsTo = array(
        'FavouriteList' => array(
            'className' => 'FavouriteList',
            'foreignKey' => 'list_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'MarvelCharacter' => array(
            'className' => 'MarvelCharacter',
            'foreignKey' => 'character_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );
    
    public function beforeSave($options = array()) {
         
    }
 
}

?>