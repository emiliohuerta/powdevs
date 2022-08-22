<?php

App::uses('MarvelApiComponent', 'Controller/Component');

class MarvelCharactersShell extends Shell
{
    //var $components = ['MarvelApi'];
    var $uses = ['MarvelCharacter'];

    public function main(){
        
        $this->out("<comment>Command for updating Marvel characters database, to execute call function updateCharactersList</comment>",2);
    }

    public function startup()
    {
        parent::startup();
        $this->MarvelApi = new MarvelApiComponent(new ComponentCollection());
    }
    
    public function updateCharactersList()
    {   
        $this->out("Executing updateCharactersList()");
        
        $page = 0;
        $limit = 20; 
        
        do {
            $size = 1;
            $charactersData = $this->MarvelApi->getCharacters(['offset' => $page * $limit]);
            $this->log($charactersData, "marvel-characters-command");
            if (isset($charactersData['code']) && $charactersData['code'] == 200) {
                
                $characters = $charactersData['data']['results'];
                $MarvelCharacter = new MarvelCharacter();
                foreach($characters as $character) {
                    
                    $MarvelCharacter->create();
                    $MarvelCharacter->id = $character['id'];
                    if(!$MarvelCharacter->exists()) {
                        
                        $data['MarvelCharacter'] = [
                            'id' => $character['id'],
                            'name' => $character['name'],
                            'thumbnail' => $character['thumbnail']['path'] . "." . $character['thumbnail']['extension'],                            
                        ];

                        if(isset($character['urls'])) {
                            foreach($character['urls'] as $url) {
                                if($url['type'] == 'wiki')
                                    $data['MarvelCharacter']['link_info'] = $url['url'];
                            }
                        }

                        if(!$MarvelCharacter->save($data)) {
                            $this->out("Error saving Character, Data:");
                            var_dump($data);
                        }
                    }


                }                
                
                $size = $charactersData['data']['count'];

            } else {
                    

                
            }
            $page++;
        } while ($size > 0 || $page == 90);
        
        
    }

    

}