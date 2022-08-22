<?php

App::uses('AppController', 'Controller');

class FavouritesListsController extends AppController {


	//public $uses = array('FavouriteList', 'MarvelCharacter', 'CharacterFavourite');

    public function beforeFilter() {
        parent::beforeFilter();        
    }

    // List of user created lists
    public function index() {
       
    }

    public function list() {
        
        $this->layout = false; $this->autoRender = false;
        $this->response->type('json');
        
        if(!isset($this->request->params['pass'][0])) {
            $this->response->statusCode(400);
            // This responses can be moved to a ApiResponseComponent and just call for example "ApiResponse::notFound()"
            return $this->response->body(json_encode([
                'success' => false,
                'httpCode' => 400,
                'message' => 'Required user ID!'
            ]));
        }

        
        $myLists = $this->FavouriteList->getUserFavourites($this->request->params['pass'][0], 'all');
        
        if (empty($myLists)) {
            $this->response->statusCode(404);
            // This responses can be moved to a ApiResponseComponent and just call for example "ApiResponse::notFound()"
            return $this->response->body(json_encode([
                'success' => true,
                'httpCode' => 404,
                'message' => 'No results found!'
            ]));
        }

        // Not the best solution
        $newListArray = [];
        foreach ($myLists as $list) {
            $newListArray[$list['FavouriteList']['id']] = $list['FavouriteList']['name'] . " (" . count($list['CharacterFavourite']) . ")";
        }
        // Response
        $this->response->statusCode(200);        
        return $this->response->body(json_encode($newListArray)); 
    }

    public function add() {
        $this->layout = false; $this->autoRender = false;

        if ($this->request->is('post')) {
            
            $this->response->type('json');
            $this->FavouriteList->create();

            $this->FavouriteList->set($this->request->data['FavouriteList']);
			if ($this->FavouriteList->validates()) {
                if ($this->FavouriteList->save($this->request->data)) {
                    // Response
                    $this->response->statusCode(201);        
                    return $this->response->body(json_encode(
                        ['id' => $this->FavouriteList->getLastInsertID()]
                    )); 
                } else {
                    $errors = 'Something went wrong';
                }
            } else {                
                $errors = $this->FavouriteList->invalidFields();				
            }

            $this->response->statusCode(400);        
            return $this->response->body(json_encode(
                ['errors' => $errors]
            ));
        }
    }

    public function addCharacterToFavourites() {

        $this->layout = false; $this->autoRender = false;

        if ($this->request->is('post') && !empty($this->request->data )) {
            
            $this->response->type('json');
            $this->log($this->request->data, "logData");
          
            foreach($this->request->data['listsIds'] as $listId) {
                
                $list = $this->FavouriteList->findById($listId);
                $list['CharacterFavourite'][]['character_id'] = $this->request->data['characterId'];
                $this->FavouriteList->saveAll($list);
            }

        }

    }

    public function detail($listId) {
        
        $this->layout = false; $this->autoRender = false;
        $this->response->type('json');
        
        if(!isset($listId)) {
            $this->response->statusCode(400);
            // This responses can be moved to a ApiResponseComponent and just call for example "ApiResponse::notFound()"
            return $this->response->body(json_encode([
                'success' => false,
                'httpCode' => 400,
                'message' => 'No list was provided!'
            ]));
        }
        
        $favourites = $this->CharacterFavourite->find('all', [
            'conditions' => [
                'list_id' => $listId
            ]
        ]);

        $favourites = Set::combine($favourites, '{n}.MarvelCharacter.id', '{n}.MarvelCharacter');
 
        
        // Response
        $this->response->statusCode(200);        
        return $this->response->body(json_encode($favourites)); 
    }

 
}
