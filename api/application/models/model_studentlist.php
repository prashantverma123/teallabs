<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 */
 
class Model_Studentlist extends MY_Model
{
  protected $_table = 'studentmaster';
  protected $primary_key = 'id';
  protected $return_type = 'array';
  protected $after_get = array('remove_sesitive_data');
  protected $before_create = array('prep_data');
  protected $before_update = array('update_timestamp');

  protected  function remove_sesitive_data($studentmaster){

      unset($studentmaster['insert_date']);
      unset($studentmaster['update_date']);
      unset($studentmaster['ip']);
      return $studentmaster;
    }

  protected  function prep_data($studentmaster){

        $studentmaster['ip'] = $this->input->ip_address();
        $studentmaster['insert_date'] = date('Y-m-d H:i:s');
        $studentmaster['update_date'] = date('Y-m-d H:i:s');
        return $studentmaster;
    }

  protected  function update_timestamp($studentmaster){
        $studentmaster['update_date'] = date('Y-m-d H:i:s');
        return $studentmaster;
    }

}
