<?php

class user{

    private  $_db,
             $_data,
             $_sessionName,
             $_cookieName,
             $_isLoggedIn;
    public function __construct($user = null)
    {
        $this->_cookieName = config::get('remember/cookie_name');
        $this->_sessionName = config::get('session/session_name');
        $this->_db = DB::getInstance();

        if(!$user)
        {
            if(session::exist($this->_sessionName))
            {
                $user = session::get($this->_sessionName);
            }

            if($this->find($user))
            {
                $this->_isLoggedIn = true;
            }
            else{
                    // process logout
            }
        }
        else{
            $this->find($user);
        }
    }

    public function hasPermissions($key)
    {
        $group = $this->_db->get('groups', array('id', '=', $this->data()->groups));

        if($group->count())
        {
            $permissions = json_decode($group->first()->permissions, true);

            if($permissions[$key] == true)
            {
                return true;
            }
        }
        return false;
        
    }

    public function create($fields = array())
    {
        $test = $this->_db->insert('users', $fields);
        if(!$test)
        {
            throw new Exception('there was some problem creating your account');
        }
    }

    public function exists()
    {
        return (!empty($this->_data)) ? true : false;
    }

    public function find($user = null)
    {
        if($user)
        {
            $field = (is_numeric($user)) ? 'id' : 'username';

            $data = $this->_db->get('users', array($field, '=', $user));

            if($data->count())
            {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }

    public function login($username = null , $password = null, $remember = false)
    {
        if(!$username && !$password && $this->exists())
        {
            session::put($this->_sessionName, $this->data()->id);
            
        }
        else {
            $user = $this->find($username);
        if($user)
        {
            if($this->data()->password === hash::make($password , $this->data()->salt))
            {
                session::put($this->_sessionName, $this->data()->id);

                if($remember)
                {
                    $hash = hash::unique();

                    $hashCheck = $this->_db->get('sessions', array('user_id', '=', $this->data()->id));

                    if(!$hashCheck->count())
                    {
                        $this->_db->insert('sessions', array(
                            'user_id' => $this->data()->id,
                            'hash' => $hash
                        ));
                    }
                    else{
                        $hash = $hashCheck->first()->hash;
                    }

                    cookie::put($this->_cookieName, $hash, config::get('remember/cookie_expiry'));
                }
                return true;
            }
        }
    }
        return false;
    }

    public function update($fields = array(), $id = null)
    {

        if(!$id && $this->isLoggedIn())
        {
            $id = $this->data()->id;
        }

        if(!$this->_db->update('users', $id, $fields))
        {
            throw new Exception('there was problem updating your information');
        }
    }

    public function data()
    {
        return $this->_data;
    }

    public function logout()
    {
        session::delete($this->_sessionName);
        // die($this->_cookieName);
        cookie::delete($this->_cookieName);
        $this->_db->delete('sessions', array('user_id', '=', $this->data()->id));
    }

    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }
}
