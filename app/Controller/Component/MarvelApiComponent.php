<?php
App::uses('Component', 'Controller');

class MarvelApiComponent extends Component {

    public $components = ['Curl'];

    public function getCharacters($params = []) {
        $params += $this->authParams();        
        return $this->Curl->get(MARVEL_API_GET_CHARACTERS_URL, $params);
    }

    private function authParams() {
        $now = new DateTime();
        $ts = $now->getTimestamp();
        $hash = md5($ts . MARVEL_API_PRIVATE_KEY . MARVEL_API_PUBLIC_KEY);
        return [
            'apikey' => MARVEL_API_PUBLIC_KEY,
            'hash' => $hash,
            'ts' => $ts
        ];
    }

}