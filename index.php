<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="bootstrap/css/bootstrap-social.css" rel="stylesheet">
        <link href="bootstrap/css/font-awesome.css" rel="stylesheet">
    </head>
    <body>
        
        
        
        <div class="container">
    <div class="row">
        <div class="col-md-12">
            <h4>Harness Tree</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="pull-left">
                <button onclick="GetNewNodeScreen(0)" class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal"><i class="fa fa-plus"></i> Add Top Nodes</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
          
 
            <div class="records_content">
                
                
                 <?php
        
        include "menu.php";
        
        echo display_children(0,0);
        ?>
            </div>
        </div>
    </div>
</div>
    
<div class="modal fade" id="new_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">New Company Node</h4>
            </div>
            <div class="modal-body">
 
              
 
                <div class="form-group">
                    <label for="update_last_name">Company</label>
                    <input type="text" id="node_text" placeholder="Node Text" class="form-control"/>
                </div>
 
 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="newTopNode()" >Save Changes</button>
                <input type="hidden" id="hidden_node_id">
            </div>
        </div>
    </div>
</div>
        
<div class="modal fade" id="new_loan_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">New Node</h4>
            </div>
            <div class="modal-body">
 
              
 
                <div class="form-group">
                    <label for="update_last_name">Company</label>
                    <input type="text" id="node_text" placeholder="Node Text" class="form-control"/>
                </div>
 
                <div class="form-group">
                    <label for="loan_interest">Company Description</label>
                    <input type="text" id="node_description" placeholder="Node Description" class="form-control"/>
                </div>
                
                   <div class="form-group">
                    <label for="menu_code">Menu Code</label>
                    <input type="text" id="menu_code" placeholder="Menu Code" class="form-control"/>
                </div>
 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="newNode()" >Save Changes</button>
                <input type="hidden" id="hidden_node_id">
            </div>
        </div>
    </div>
</div> 
        
        
        
<!-- Jquery JS file -->
<script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
 
<!-- Bootstrap JS file -->
<script type="text/javascript" src="js/bootstrap.min.js"></script>
 
<!-- Custom JS file -->
<script type="text/javascript" src="js/harness.js"></script>

    </body>
</html>
