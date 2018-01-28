<?php

    /******************************************
    *      Codeigniter 3 Simple Login         *
    *   Developer  :  rudiliucs1@gmail.com    *
    *        Copyright © 2017 Rudi Liu        *
    *******************************************/

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Ticket extends CI_Controller {

    public function __Construct() {
        parent::__Construct();
        if(!$this->session->userdata('logged_in')) {
            redirect(base_url());
        }

        if($this->session->userdata('role') != 'admin'){
            redirect(base_url());
        }

        $this->load->model('ticket_model');
    }
    

    private function ajax_checking(){
        if (!$this->input->is_ajax_request()) {
            redirect(base_url());
        }
    }

    public function ticket_list(){

        $data = array(
            'formTitle' => 'Ticket Management',
            'title' => 'Ticket Management',
            'tickets' => $this->ticket_model->get_ticket_list(),
        );

        $this->load->view('frame/header_view');
        $this->load->view('frame/sidebar_nav_view');
        $this->load->view('ticket/ticket_list', $data);

    }

    function add_ticket(){
        $this->ajax_checking();

        $postData = $this->input->post();
        $insert = $this->ticket_model->insert_ticket($postData);
        if($insert['status'] == 'success')
            $this->session->set_flashdata('success', 'ticket '.$postData['ticket'].' has been successfully created!');

        echo json_encode($insert);
    }

    function update_ticket_details(){
        $this->ajax_checking();

        $postData = $this->input->post();
        $update = $this->ticket_model->update_ticket_details($postData);
        if($update['status'] == 'success')
            $this->session->set_flashdata('success', 'ticket '.$postData['ticket'].'`s details have been successfully updated!');

        echo json_encode($update);
    }

    function deactivate_ticket($ticket,$id){
        $this->ajax_checking();

        $update = $this->ticket_model->deactivate_ticket($ticket,$id);
        if($update['status'] == 'success')
            $this->session->set_flashdata('success', 'ticket '.$ticket.' has been successfully deleted!');

        echo json_encode($update);
    }

      
}

/* End of file */
