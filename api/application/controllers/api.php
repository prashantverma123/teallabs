<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Api extends REST_Controller{

      function __construct(){
              parent::__construct();
            $this->load->helper('my_api');
      }


      function studentlist_get(){
        //$id = $this->uri->segment(3);//get('id');
        // $student = array(
        //   1=>array('first'=>'Prashant','last_name'=>'Verma'),
        //   2=>array('first'=>'Shivangi','last_name'=>'Verma'),
        // );
        $this->load->model('Model_Studentlist');
        $list= $this->Model_Studentlist->get_all();
        if(isset($list)){
          $this->response(array('status'=>'success','message'=>$list));
        }

        else{
          $this->response(array('status'=>'failure','message'=>" Student List Not Found"),REST_Controller::HTTP_NOT_FOUND);
        }

      }

      function studentdata_get(){
        $student_id = $this->uri->segment(3);//get('id');

        $this->load->model('Model_Studentdata');
        $tab_data= $this->Model_Studentdata->get_many_by(array( 'student_id' => $student_id));
        $data = $this->getClassStats($tab_data);
        $count = count($data);
        if(isset($data)){
          $this->response(array('status'=>'success','message'=>array('count'=>$count,'data'=>$data)));
        }

        else{
          $this->response(array('status'=>'failure','message'=>" Student List Not Found"),REST_Controller::HTTP_NOT_FOUND);
        }

      }

      function processAssesmentId($assesment_id){
        $values = explode("06",$assesment_id);
        $subject_Id = $values[0];
        $second_half = explode("A01",$values[1]);
        $section = $second_half[0];
        $third_half = explode("T",$second_half[1]);
        $fourth_half = explode("t",$third_half[1]);
        $timestamp = (int)$fourth_half[0];
        $submodule_id = 't';
        if(count($fourth_half)<2){
          $submodule_id = $submodule_id. 5;
        }
        else {
          $submodule_id = $submodule_id. (int)$fourth_half[1];
        }

        $processed_id = array(
              "subject_Id" => $subject_Id,
              "section" => $section,
              "timestamp" => $timestamp ,
              "submodule_id" => $submodule_id,
          );

        return $processed_id;
      }

      function processData($data_array){
        $count  = count($data_array);
        $data_Si=[];		//  MSI - Simple Interest
   		  $data_Men=[];		//  MMEN - Mensuration
   		  $data_Alg=[];		//  MAlg - Algebra
   		  $data_Set=[];		//  MSET - Sets
   		  $data_Rap=[];		//  MRAP - Ration and Proportions
   		  $data_Ns=[];		//  MNS - Number Systems
   		  $data_Fra=[];


        for ($i=0; $i < $count; $i++) {
          $processed_id= $this->processAssesmentId($data_array[$i]['assesmentItem_id']);
          $obj = array(
    				"assesmentItem_id" => $data_array[$i]['assesmentItem_id'],
    				"correct"=> $data_array[$i]['correct']=='1'?1:0,
    				"difficulty"=>$data_array[$i]['difficulty'],
    				"time_started"=> (int)($data_array[$i]['time_started']),
    				"time_taken"=>(int)($data_array[$i]['time_taken']),
    				"subject_Id"=> $processed_id['subject_Id'],
    				"section" => $processed_id['section'],
    				"submodule_id"=> $processed_id['submodule_id']
    			);
          switch($processed_id['subject_Id']) {
    				    case "MSI":
    								array_push($data_Si,$obj);
    				        break;
    				    case "MMEN":
    								array_push($data_Men,$obj);
    				        break;
    						case "MAlg":
    								array_push($data_Alg,$obj);
    				        break;
    						case "MSET":
    								array_push($data_Set,$obj);
    								break;
    						case "MRAP":
    								array_push($data_Rap,$obj);
    				        break;
    						case "MNS":
    								array_push($data_Ns,$obj);
    				        break;
    						case "MFRA":
    								array_push($data_Fra,$obj);
    				        break;
    				    default:
    				         echo "HI";
    								 break;
    					}

        }
        $processed_Data = array(
  					'total_count' => $count,
  					'data_Si' =>$data_Si,
  					'data_Men' =>$data_Men,
  					'data_Alg' =>$data_Alg,
  					'data_Set' =>$data_Set,
  					'data_Rap' =>$data_Rap,
  					'data_Ns' =>$data_Ns,
  					'data_Fra' => $data_Fra,
          );
        return $processed_Data;
      }

      function filterCorrectData($data_array){
        $filterd_array =  array_values(array_filter($data_array,function($obj){return $obj['correct'] == 1;}));

        return $filterd_array;
      }

      function filterSubTopicData($data_array){


          $data = array(
            'data_t1' => array_values(array_filter($data_array,function($obj){return $obj['submodule_id'] == "t1";})),
            'data_t2' => array_values(array_filter($data_array,function($obj){return $obj['submodule_id'] == "t2";})),
            'data_t3' => array_values(array_filter($data_array,function($obj){return $obj['submodule_id'] == "t3";})),
            'data_t4' => array_values(array_filter($data_array,function($obj){return $obj['submodule_id'] == "t4";})),
            'data_t5' => array_values(array_filter($data_array,function($obj){return $obj['submodule_id'] == "t5";})),
            'data_t6' => array_values(array_filter($data_array,function($obj){return $obj['submodule_id'] == "t6";})),
            'data_t7' => array_values(array_filter($data_array,function($obj){return $obj['submodule_id'] == "t7";}))

          );

          return $data;
      }

      function getClassStats($tab_data){

        $my_data_Si = $this->processData($tab_data)['data_Si'];
        $my_data_Men = $this->processData($tab_data)['data_Men'];
        $my_data_Ns = $this->processData($tab_data)['data_Ns'];
        $my_data_Rap = $this->processData($tab_data)['data_Rap'];
        $my_data_Set = $this->processData($tab_data)['data_Set'];
        $my_data_Fra = $this->processData($tab_data)['data_Fra'];
        $my_data_Alg = $this->processData($tab_data)['data_Alg'];


          $si_subtopic_data = $this->filterSubTopicData($my_data_Si);
          $men_subtopic_data = $this->filterSubTopicData($my_data_Men);
          $alg_subtopic_data = $this->filterSubTopicData($my_data_Alg);
          $set_subtopic_data = $this->filterSubTopicData($my_data_Set);
          $rap_subtopic_data = $this->filterSubTopicData($my_data_Rap);
          $ns_subtopic_data = $this->filterSubTopicData($my_data_Ns);
          $fra_subtopic_data = $this->filterSubTopicData($my_data_Fra);

          $correct_topic_wise_attempts = $this->filterCorrectData($my_data_Si);
         $total_topic_wise_attempts = array(
  				 'simpleInterest'=> count($my_data_Si),
  				 'mensuration'=> count($my_data_Men),
  				 'algebra'=>count($my_data_Alg),
  				 'sets'=> count($my_data_Set),
  				 'ratioAndProportion'=>count($my_data_Rap),
  				 'numberSystem'=>count($my_data_Ns),
  				 'fraction'=> count($my_data_Fra)
  			 );

         $total_sub_topic_wise_attempts = array(
  				 'simpleInterest'=> array(
             "t1"=>count($si_subtopic_data['data_t1']),"t2"=>count($si_subtopic_data['data_t2']),
             "t3"=>count($si_subtopic_data['data_t3']),"t4"=>count($si_subtopic_data['data_t4']),
             "t5"=>count($si_subtopic_data['data_t5']),"t6"=>count($si_subtopic_data['data_t6']),
             "t7"=>count($si_subtopic_data['data_t7'])
           ),
  				 'mensuration'=> array(
             "t1"=>count($men_subtopic_data['data_t1']),"t2"=>count($men_subtopic_data['data_t2']),
             "t3"=>count($men_subtopic_data['data_t3']),"t4"=>count($men_subtopic_data['data_t4']),
             "t5"=>count($men_subtopic_data['data_t5']),"t6"=>count($men_subtopic_data['data_t6']),
             "t7"=>count($men_subtopic_data['data_t7'])
           ),
  				 'algebra'=>array(
             "t1"=>count($alg_subtopic_data['data_t1']),"t2"=>count($alg_subtopic_data['data_t2']),
             "t3"=>count($alg_subtopic_data['data_t3']),"t4"=>count($alg_subtopic_data['data_t4']),
             "t5"=>count($alg_subtopic_data['data_t5']),"t6"=>count($alg_subtopic_data['data_t6']),
             "t7"=>count($alg_subtopic_data['data_t7'])
           ),
  				 'sets'=> array(
             "t1"=>count($set_subtopic_data['data_t1']),"t2"=>count($set_subtopic_data['data_t2']),
             "t3"=>count($set_subtopic_data['data_t3']),"t4"=>count($set_subtopic_data['data_t4']),
             "t5"=>count($set_subtopic_data['data_t5']),"t6"=>count($set_subtopic_data['data_t6']),
             "t7"=>count($set_subtopic_data['data_t7'])
           ),
  				 'ratioAndProportion'=>array(
             "t1"=>count($rap_subtopic_data['data_t1']),"t2"=>count($rap_subtopic_data['data_t2']),
             "t3"=>count($rap_subtopic_data['data_t3']),"t4"=>count($rap_subtopic_data['data_t4']),
             "t5"=>count($rap_subtopic_data['data_t5']),"t6"=>count($rap_subtopic_data['data_t6']),
             "t7"=>count($rap_subtopic_data['data_t7'])
           ),
  				 'numberSystem'=>array(
             "t1"=>count($ns_subtopic_data['data_t1']),"t2"=>count($ns_subtopic_data['data_t2']),
             "t3"=>count($ns_subtopic_data['data_t3']),"t4"=>count($ns_subtopic_data['data_t4']),
             "t5"=>count($ns_subtopic_data['data_t5']),"t6"=>count($ns_subtopic_data['data_t6']),
             "t7"=>count($ns_subtopic_data['data_t7'])
           ),
  				 'fraction'=> array(
             "t1"=>count($fra_subtopic_data['data_t1']),"t2"=>count($fra_subtopic_data['data_t2']),
             "t3"=>count($fra_subtopic_data['data_t3']),"t4"=>count($fra_subtopic_data['data_t4']),
             "t5"=>count($fra_subtopic_data['data_t5']),"t6"=>count($fra_subtopic_data['data_t6']),
             "t7"=>count($fra_subtopic_data['data_t7'])
           )
  			 );

         $correct_topic_wise_attempts = array(
          'simpleInterest'=> count($this->filterCorrectData($my_data_Si)),
          'mensuration'=> count($this->filterCorrectData($my_data_Men)),
          'algebra'=>count($this->filterCorrectData($my_data_Alg)),
          'sets'=> count($this->filterCorrectData($my_data_Set)),
          'ratioAndProportion'=>count($this->filterCorrectData($my_data_Rap)),
          'numberSystem'=>count($this->filterCorrectData($my_data_Ns)),
          'fraction'=> count($this->filterCorrectData($my_data_Fra))
        );

        $correct_sub_topic_wise_attempts = array(
          'simpleInterest'=> array(
            "t1"=>count($this->filterCorrectData($si_subtopic_data['data_t1'])),"t2"=>count($this->filterCorrectData($si_subtopic_data['data_t2'])),
            "t3"=>count($this->filterCorrectData($si_subtopic_data['data_t3'])),"t4"=>count($this->filterCorrectData($si_subtopic_data['data_t4'])),
            "t5"=>count($this->filterCorrectData($si_subtopic_data['data_t5'])),"t6"=>count($this->filterCorrectData($si_subtopic_data['data_t6'])),
            "t7"=>count($this->filterCorrectData($si_subtopic_data['data_t7']))
          ),
          'mensuration'=> array(
            "t1"=>count($this->filterCorrectData($men_subtopic_data['data_t1'])),"t2"=>count($this->filterCorrectData($men_subtopic_data['data_t2'])),
            "t3"=>count($this->filterCorrectData($men_subtopic_data['data_t3'])),"t4"=>count($this->filterCorrectData($men_subtopic_data['data_t4'])),
            "t5"=>count($this->filterCorrectData($men_subtopic_data['data_t5'])),"t6"=>count($this->filterCorrectData($men_subtopic_data['data_t6'])),
            "t7"=>count($this->filterCorrectData($men_subtopic_data['data_t7']))
          ),
          'algebra'=>array(
            "t1"=>count($this->filterCorrectData($alg_subtopic_data['data_t1'])),"t2"=>count($this->filterCorrectData($alg_subtopic_data['data_t2'])),
            "t3"=>count($this->filterCorrectData($alg_subtopic_data['data_t3'])),"t4"=>count($this->filterCorrectData($alg_subtopic_data['data_t4'])),
            "t5"=>count($this->filterCorrectData($alg_subtopic_data['data_t5'])),"t6"=>count($this->filterCorrectData($alg_subtopic_data['data_t6'])),
            "t7"=>count($this->filterCorrectData($alg_subtopic_data['data_t7']))
          ),
          'sets'=> array(
            "t1"=>count($this->filterCorrectData($set_subtopic_data['data_t1'])),"t2"=>count($this->filterCorrectData($set_subtopic_data['data_t2'])),
            "t3"=>count($this->filterCorrectData($set_subtopic_data['data_t3'])),"t4"=>count($this->filterCorrectData($set_subtopic_data['data_t4'])),
            "t5"=>count($this->filterCorrectData($set_subtopic_data['data_t5'])),"t6"=>count($this->filterCorrectData($set_subtopic_data['data_t6'])),
            "t7"=>count($this->filterCorrectData($set_subtopic_data['data_t7']))
          ),
          'ratioAndProportion'=>array(
            "t1"=>count($this->filterCorrectData($rap_subtopic_data['data_t1'])),"t2"=>count($this->filterCorrectData($rap_subtopic_data['data_t2'])),
            "t3"=>count($this->filterCorrectData($rap_subtopic_data['data_t3'])),"t4"=>count($this->filterCorrectData($rap_subtopic_data['data_t4'])),
            "t5"=>count($this->filterCorrectData($rap_subtopic_data['data_t5'])),"t6"=>count($this->filterCorrectData($rap_subtopic_data['data_t6'])),
            "t7"=>count($this->filterCorrectData($rap_subtopic_data['data_t7']))
          ),
          'numberSystem'=>array(
            "t1"=>count($this->filterCorrectData($ns_subtopic_data['data_t1'])),"t2"=>count($this->filterCorrectData($ns_subtopic_data['data_t2'])),
            "t3"=>count($this->filterCorrectData($ns_subtopic_data['data_t3'])),"t4"=>count($this->filterCorrectData($ns_subtopic_data['data_t4'])),
            "t5"=>count($this->filterCorrectData($ns_subtopic_data['data_t5'])),"t6"=>count($this->filterCorrectData($ns_subtopic_data['data_t6'])),
            "t7"=>count($this->filterCorrectData($ns_subtopic_data['data_t7']))
          ),
          'fraction'=> array(
            "t1"=>count($this->filterCorrectData($fra_subtopic_data['data_t1'])),"t2"=>count($this->filterCorrectData($fra_subtopic_data['data_t2'])),
            "t3"=>count($this->filterCorrectData($fra_subtopic_data['data_t3'])),"t4"=>count($this->filterCorrectData($fra_subtopic_data['data_t4'])),
            "t5"=>count($this->filterCorrectData($fra_subtopic_data['data_t5'])),"t6"=>count($this->filterCorrectData($fra_subtopic_data['data_t6'])),
            "t7"=>count($this->filterCorrectData($fra_subtopic_data['data_t7']))
          )
        );

        $data_for_chart = array(
          "total_count" => count($tab_data),
          "total_topic_wise_attempts" => $total_topic_wise_attempts,
          "correct_topic_wise_attempts"=>$correct_topic_wise_attempts,
          "total_sub_topic_wise_attempts"=> $total_sub_topic_wise_attempts,
          "correct_sub_topic_wise_attempts"=>$correct_sub_topic_wise_attempts,

        );
        return $data_for_chart;


      }

      function classdata_get(){
        $id= $this->uri->segment(3);//get('id');
        $batch_size= $this->uri->segment(4);

        $min_id =$id-$batch_size;
        $this->load->model('Model_Studentdata');
        $tab_data= $this->Model_Studentdata->get_many_by(array('id <'. $id.' AND id >' =>$min_id));


        $data = $this->getClassStats($tab_data);
        // for()
        $count = count($data);
        if(isset($data)){
          $this->response(array('status'=>'success','message'=>array('count'=>$count,'data'=>$data)));
        }

        else{
          $this->response(array('status'=>'failure','message'=>" Student List Not Found"),REST_Controller::HTTP_NOT_FOUND);
        }

      }





}
