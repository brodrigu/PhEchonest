<?php

class PhEchonest_Artist extends PhEchonest_Abstract
{
    protected static $_resource = 'artist';
    protected $_id;
    protected $_name;
    
    public function __construct($id, $name = null)
    {
        $this->_id = $id;
        
        if ($name) {
            $this->_name = $name;
        }
    }
    
    /**
     * magic getter
     */
    public function __get($accessor)
    {
        $accessor = "_".$accessor;
        return $this->$accessor;
    }
    
    public function getTracks($limit = 20, $offest = 0)
    {
        $method = 'audio';
        $query = array(
            'id' => $this->_id,
            'results' => $limit,
            'start' => $offset
            );
        $result = self::makeRequest($method, $query);
        return $result['audio'];
    }
    
    /**
     * search for an artist by name
     */
    public static function searchByName($name)
    {
        $method = 'search';
        $query = array(
            'name' => $name
        );
        $result = self::makeRequest($method, $query);
        
        $artists = array();
        foreach ($result['artists'] as $artist) 
        {
            $artists[] = new PhEchonest_Artist($artist['id'], $artist['name']);
        }
        return $artists;
    }
}