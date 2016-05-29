<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 10/01/2016
 * Time: 2:44
 */
$config = array(
    'city_put'=>array(
        array('field'=>'name','label'=>'name','rules'=>'trim|required|max_length[50]'),
        array('field'=>'city_tier','label'=>'city_tier','rules'=>'trim|required|max_length[50]'),
        // array('field'=>'first_name','label'=>'first_name','rules'=>'trim|required|max_length[50]'),
        // array('field'=>'last_name','label'=>'last_name','rules'=>'trim|required|max_length[50]'),
        // array('field'=>'phone_number','label'=>'phone_number','rules'=>'trim|required|alpha_dash')
    ),
    'city_post'=>array(
        array('field'=>'name','label'=>'name','rules'=>'trim|required|max_length[50]'),
        array('field'=>'city_tier','label'=>'city_tier','rules'=>'trim|required|max_length[50]'),
        // array('field'=>'last_name','label'=>'last_name','rules'=>'trim|max_length[50]'),
        // array('field'=>'phone_number','label'=>'phone_number','rules'=>'trim|alpha_dash')
    ),
    'orders_post'=>array(
        array('field'=>'name','label'=>'name','rules'=>'trim|required|max_length[50]'),
        array('field'=>'lead_source','label'=>'lead_source','rules'=>'trim|required|max_length[50]'),
        array('field'=>'mobile_no','label'=>'mobile_no','rules'=>'trim|required|max_length[50]'),
        array('field'=>'alternate_no','label'=>'alternate_no','rules'=>'trim|max_length[50]'),
        array('field'=>'email_id','label'=>'email_id','rules'=>'trim|required|valid_email'),
        array('field'=>'address','label'=>'address','rules'=>'trim|max_length[50]'),
        array('field'=>'landmark','label'=>'name','landmark'=>'trim|max_length[50]'),
        array('field'=>'location','label'=>'location','rules'=>'trim|max_length[50]'),
        array('field'=>'city','label'=>'city','rules'=>'trim|required|max_length[50]'),
        array('field'=>'state','label'=>'state','rules'=>'trim|max_length[50]'),
        array('field'=>'pincode','label'=>'pincode','rules'=>'trim|max_length[50]'),
        array('field'=>'service','label'=>'service','rules'=>'trim|required|max_length[50]'),

        // array('field'=>'last_name','label'=>'last_name','rules'=>'trim|max_length[50]'),
        // array('field'=>'phone_number','label'=>'phone_number','rules'=>'trim|alpha_dash')
    ),
);
