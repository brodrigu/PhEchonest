<?php

class PhEchonest_Artist extends PhEchonest_Abstract
{
    protected static $_resource = 'artist';
    protected $_id;
    protected $_name;
    
    /**
     * constructor
     */
    public function __construct($id, $name = null)
    {
        $this->_id = $id;
        
        if (!$name) {
            $profile = $this->getProfile();
            $name = $profile['name'];
        }
        $this->_name = $name;
    }
    
    /**
     * magic getter
     */
    public function __get($accessor)
    {
        $accessor = "_".$accessor;
        return $this->$accessor;
    }
    
    /**
     * get tracks for this artist
     *
     * @param   $limit  int number of results to return
     * @param   $offset int number of results to skip
     * @return  array   array of track info arrays
     */
    public function getTracks($limit = 20, $offset = 0)
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
     * get profile of artist
     *
     * @return  artist profile as array
     */
    protected function getProfile()
    {
        $method = 'profile';
        $query = array(
            'id' => $this->_id
            );
        $result = self::makeRequest($method, $query);
        return $result['artist'];
    }
    
    /**
     * search for an artist by name
     *
     * @param   $name       string  name of artist to search for
     * @param   $results    int     number of results to return
     * @param   $fuzzy      bool    match similar artist names
     * @return  array       array of artist objects
     */
    public static function searchByName($name, $results = 20, $fuzzy = true)
    {
        $method = 'search';
        $query = array(
            'name' => $name,
            'fuzzy_match' => ($fuzzy ? 'true' : 'false'),
            'results' => $results
            
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