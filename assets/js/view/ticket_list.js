    window.onload = hideErrorMessages();

    function hideErrorMessages(){
        $("#error_ticket_code").hide();
        $("#error_ticket_code2").hide();
        $("#error_ticket_code3").hide();
        $("#error_role").hide();
        $("#edit-error_ticket_code").hide();
        $("#edit-error_ticket_code2").hide();
        $("#edit-error_ticket_code3").hide();
        $("#edit-error_role").hide();
        hide_loading();
    }

    $(document).ready( function () {

        //$('#dataTables-ticket-log').DataTable();
        $('#dataTables-ticket-list').DataTable({
            "bFilter": true,
            "paging":   false,
            //"iDisplayLength": 20,
            "order": [[ 0, "asc" ]]
            //"bDestroy": true,
        });
     } );

    function edit_ticket_popup(ticket_code,id,role){
        $( "#edit-ticket_code" ).val(ticket_code);
        $( "#edit-ticket-id" ).val(id);
        if(role=='seller')
            roleOption = "<option value='seller' selected>Seller</option><option value='ticket'>Seller</option>";
        else
            roleOption = "<option value='buyer'>Buyer</option><option value='ticket' selected>Seller</option>";

        $( "#edit-role" ).html(roleOption);
        $('#editticketSubmit').attr("onclick","update_ticket_details("+id+")");
    }

    function deactivate_confirmation(ticket_code,id){
        $( "#ticket-ticket_code" ).html(ticket_code);
        $('#deactivateYesButton').attr("onclick","deactivate_submit('"+ticket_code+"',"+id+")");
    }

    function reset_confirmation(ticket_code,id){
        $( "#reset-ticket-ticket_code" ).html(ticket_code);
        $('#resetYesButton').attr("onclick","reset_submit('"+ticket_code+"',"+id+")");
    }

    function deactivate_submit(ticket_code,id){
        show_loading();
            $.ajax({
            url: $("#base-url").val()+"admin/deactivate_ticket/"+ticket_code+"/"+id,
            cache: false,
            success: function (result) {
                var result = $.parseJSON(result);
                if(result.status=='success'){
                    location.reload();
                }
                else{
                    alert("Oops there is something wrong!");
                }
            },
            error: ajax_error_handling
        });
    }

    
    function update_ticket_details(id){
        hideErrorMessages();
        show_loading();
        var i=0;
        var ticket_code = $('#edit-ticket_code').val().trim();
        var role = $('#edit-role').val();


        if(ticket_code == ""){
            $("#edit-error_ticket_code").show();
            i++;
        }
        else if (ticket_code !== "") {
            $("#edit-error_ticket_code2").show();
            i++;
        }

       
        if(role == 0){
            $("#edit-error_role").show();
            i++;
        }

        if(i == 0){
            $.ajax({
                url: $("#base-url").val()+"admin/update_ticket_details/",
                traditional: true,
                type: "post",
                dataType: "text",
                data: {ticket_code: ticket_code, id:id, role:role},
                success: function (result) {
                    var result = $.parseJSON(result);
                    if(result.status=='success'){
                        location.reload();
                    }
                    else if(result.status=='exist'){
                        $("#edit-error_ticket_code2").show();
                        hide_loading();
                    }
                    else{
                        alert("Oops there is something wrong!");
                    }
                },
                error: ajax_error_handling
            });
        }
    }






    $( "#newticketSubmit" ).click(function() {
        hideErrorMessages();
        show_loading();
        var i=0;
        var ticket_code = $('#ticket_code').val().trim();
        var role = $('#role').val();

        if(ticket_code == ""){
            $("#error_name").show();
            i++;
        }
        else if (ticket_code !== "") {
            $("#error_name2").show();
            i++;
        }


        if(role == 0){
            $("#error_role").show();
            i++;
        }

        if(i == 0){
            $.ajax({
                url: $("#base-url").val() + "admin/add_ticket",
                traditional: true,
                type: "post",
                dataType: "text",
                data: {ticket_code:ticket_code, role:role, name:name},
                success: function (result) {
                    var result = $.parseJSON(result);
                    if(result.status=='success'){
                        location.reload();
                    }
                    else if(result.status=='exist'){
                        $("#error_ticket_code2").show();
                        hide_loading();
                    }
                    else{
                        alert("Oops there is something wrong!");
                    }
                  
                },
                error: ajax_error_handling
            });
        }else{
            hide_loading();
        }
            
    });


