<?php 
// app/Model/User.php
App::uses('AppModel', 'Model');

class MarvelCharacter extends AppModel {

    var $hasMany = array(
        'CharacterFavourite' => array(
            'className' => 'CharacterFavourite',
            'foreignKey' => 'character_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );

    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A name is required'
            )
        )        
    );

    public function beforeSave($options = array()) {
         
    }

    public function findWithOptions($options = []) {
        
        return $this->find($options['type'] ?? 'all', [
            'conditions' => $options['conditions'] ?? [],
            'fields' => $options['fields'] ?? '*',
            'limit' => $options['limit'] ?? 20
        ]);
    }

}

?>