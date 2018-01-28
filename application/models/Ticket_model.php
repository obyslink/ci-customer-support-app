<?php

    /******************************************
    *      Codeigniter 3 Simple Login         *
    *   Developer  :  rudiliucs1@gmail.com    *
    *        Copyright © 2017 Rudi Liu        *
    *******************************************/

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Ticket_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    function get_ticket_list(){
        $this->db->select('*');
        $this->db->from('ticket');
        $this->db->where('status', 1);
        $query=$this->db->get();
        return $query->result();
    }

    function get_ticket_by_id($ticketID){
        $this->db->select('*');
        $this->db->from('ticket');
        $this->db->where('ticket_id', $ticketID);
        $query=$this->db->get();
        return $query->result_array();
    }

    function validate_ticket($postData){
        $this->db->where('ticket', $postData['ticket']);
        $this->db->where('status', 1);
        $this->db->from('ticket');
        $query=$this->db->get();

        if ($query->num_rows() == 0)
            return true;
        else
            return false;
    }

    function insert_ticket($postData){

        $validate = $this->validate_ticket($postData);

        if($validate){
            $password = $this->generate_password();
            $data = array(
                'email' => $postData['email'],
                'name' => $postData['name'],
                'role' => $postData['role'],
                'created_at' => date('Y\-m\-d\ H:i:s A'),
            );
            $this->db->insert('ticket', $data);

            $message = "Here is your Ticket details:<br><br>ticket: ".$postData['ticket']."<br>Tempolary password: ".$password."<br>Please change your password after login.<br><br> you can login at ".base_url().".";
            $subject = "New Account Creation";
            $this->send_ticket($message,$subject,$postData['ticket']);

            $module = "ticket Management";
            $activity = "add new ticket ".$postData['ticket_code'];
            $this->insert_log($activity, $module);
            return array('status' => 'success', 'message' => '');

        }else{
            return array('status' => 'exist', 'message' => '');
        }

    }

    function update_ticket_details($postData){

        $oldData = $this->get_ticket_by_id($postData['id']);

        if($oldData[0]['ticket'] == $postData['ticket'])
            $validate = true;
        else
            $validate = $this->validate_ticket($postData);

        if($validate){
            $data = array(
                'ticket_code' => $postData['ticket_code'],
                'name' => $postData['name'],
                'role' => $postData['role'],
            );
            $this->db->where('ticket_id', $postData['id']);
            $this->db->update('ticket', $data);

            $record = "(".$oldData[0]['ticket']." to ".$postData['ticket'].", ".$oldData[0]['name']." to ".$postData['name'].",".$oldData[0]['role']." to ".$postData['role'].")";

            $module = "ticket Management";
            $activity = "update ticket ".$oldData[0]['ticket_code']."`s details ".$record;
            $this->insert_log($activity, $module);
            return array('status' => 'success', 'message' => $record);
        }else{
            return array('status' => 'exist', 'message' => '');
        }

    }


    function deactivate_ticket($ticket,$id){

        $data = array(
            'status' => 0,
        );
        $this->db->where('ticket_id', $id);
        $this->db->update('ticket', $data);

        $module = "ticket Management";
        $activity = "delete ticket ".$ticket;
        $this->insert_log($activity, $module);
        return array('status' => 'success', 'message' => '');

    }

    function reset_ticket_password($ticket,$id){

        $password = $this->generate_password();
        $data = array(
            'password' => md5($password),
        );
        $this->db->where('ticket_id', $id);
        $this->db->update('ticket', $data);

        $message = "Your account password has been reset.<br><br>ticket: ".$ticket."<br>Tempolary password: ".$password."<br>Please change your password after login.<br><br> you can login at ".base_url().".";
        $subject = "Password Reset";
        $this->send_ticket($message,$subject,$ticket);

        $module = "ticket Management";
        $activity = "reset ticket ".$ticket."`s password";
        $this->insert_log($activity, $module);
        return array('status' => 'success', 'message' => '');

    }

    
    function insert_log($activity, $module){
        $id = $this->session->ticketdata('ticket_id');

        $data = array(
            'fk_ticket_id' => $id,
            'activity' => $activity,
            'module' => $module,
            'created_at' => date('Y\-m\-d\ H:i:s A')
        );
        $this->db->insert('activity_log', $data);
    }

    
    function send_ticket($message,$subject,$sendTo){
        require_once APPPATH.'libraries/mailer/class.phpmailer.php';
        require_once APPPATH.'libraries/mailer/class.smtp.php';
        require_once APPPATH.'libraries/mailer/mailer_config.php';
        include APPPATH.'libraries/mailer/template/template.php';
        
        $mail = new PHPMailer(true);
        $mail->IsSMTP();
        try
        {
            $mail->SMTPDebug = 1;  
            $mail->SMTPAuth = true; 
            $mail->SMTPSecure = 'ssl'; 
            $mail->Host = HOST;
            $mail->Port = PORT;  
            $mail->ticketname = Gticket;  
            $mail->Password = GPWD;     
            $mail->SetFrom(Gticket, 'Administrator');
            $mail->Subject = "DO NOT REPLY - ".$subject;
            $mail->IsHTML(true);   
            $mail->WordWrap = 0;


            $hello = '<h1 style="color:#333;font-family:Helvetica,Arial,sans-serif;font-weight:300;padding:0;margin:10px 0 25px;text-align:center;line-height:1;word-break:normal;font-size:38px;letter-spacing:-1px">Hello, &#9786;</h1>';
            $thanks = "<br><br><i>This is autogenerated email please do not reply.</i><br/><br/>Thanks,<br/>Admin<br/><br/>";

            $body = $hello.$message.$thanks;
            $mail->Body = $header.$body.$footer;
            $mail->AddAddress($sendTo);

            if(!$mail->Send()) {
                $error = 'Mail error: '.$mail->ErrorInfo;
                return array('status' => false, 'message' => $error);
            } else { 
                return array('status' => true, 'message' => '');
            }
        }
        catch (phpmailerException $e)
        {
            $error = 'Mail error: '.$e->errorMessage();
            return array('status' => false, 'message' => $error);
        }
        catch (Exception $e)
        {
            $error = 'Mail error: '.$e->getMessage();
            return array('status' => false, 'message' => $error);
        }
        
    }

}

/* End of file */
