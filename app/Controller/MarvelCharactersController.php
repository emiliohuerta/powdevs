<?php

App::uses('AppController', 'Controller');
App::import('Model', 'MarvelCharacter');
App::import('Model', 'FavouriteList');

class MarvelCharactersController extends AppController {


	public $uses = array('MarvelCharacter', 'FavouriteList');


    public function beforeFilter() {
        parent::beforeFilter();
        //$this->Auth->allow('add', 'logout');
    }

    public function index() {
        // Lists for modal 
        $myLists = $this->FavouriteList->getUserFavourites(AuthComponent::user('id'));
        $this->set('userFavouriteLists', $myLists);
    }


    /**
     * Fetch list of characters from Marvel Api
     */
	public function list() {

        $this->layout = false; $this->autoRender = false;
        $this->response->type('json');
        
        $this->MarvelCharacter = new MarvelCharacter();

        $options = ['limit' => 100];        
        if(!empty($this->request->query['search'])) {
            $options["conditions"] = ["MarvelCharacter.name LIKE" => $this->request->query['search'] . "%"];
        }
        
        $characters = $this->MarvelCharacter->findWithOptions($options);
        
        if (empty($characters)) {
            $this->response->statusCode(404);
            // This responses can be moved to a ApiResponseComponent and just call for example "ApiResponse::notFound()"
            return $this->response->body(json_encode([
                'success' => true,
                'httpCode' => 404,
                'message' => 'No results found!'
            ]));
        }
        // Just to make a better looking array
        $characters = Set::combine($characters, '{n}.MarvelCharacter.id', '{n}.MarvelCharacter');
        
        // Response
        $this->response->statusCode(200);
        return $this->response->body(json_encode($characters)); 

    }
 
}
