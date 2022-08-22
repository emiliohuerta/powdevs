<?php 
// app/Model/User.php
App::uses('AppModel', 'Model');

class FavouriteList extends AppModel {


    var $hasMany = array(
        'CharacterFavourite' => array(
            'className' => 'CharacterFavourite',
            'foreignKey' => 'list_id',
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
        ),
        'user_id' => array(
            'required' => array(
                'rule' => 'notBlank',
                'message' => 'A user Id is required'
            )
        )        
    );

    public function beforeSave($options = array()) {
         
    }

    public function getUserFavourites($userId, $type = 'list') {
        return $this->find($type, [
            'conditions' => [
                'user_id'  => $userId
            ],
            'fields' => ['id', 'name']

        ]);
    }
}

?>