<?php
		
		error_reporting(1);
		ini_set('display_errors', 1);	
        include 'include/conn.php';
      


function simple_textbox($component_name, $required_status, $box_inp_type, $i, $value)
{ 
    if ($required_status != "Not Required") {
        if ($box_inp_type == "Simple Text Box") {
            $required = "";
            $str = "";

            if ($required_status == 'Required') {
                $required = "required";
                $str = "*";
            }
           $component_name=trim($component_name);
            echo ' <div class="input-group  mb-3">';
            echo ' <div class="input-group-prepend">';
            echo ' <span class="input-group-text" id="inputGroup-sizing-sm">' . $component_name . $str . ' :</span> ';
            echo ' </div> ';
            echo '   <input type="text" id="test' . $i . '" ' . $required . 'name="' . $component_name . '" value="' . $value . '" class="form-control" placeholder="Enter ' . $component_name . '" aria-label="Small" aria-describedby="inputGroup-sizing-sm" required>';
            echo ' </div> ';
            // echo ' <script> ';
            // echo ' $( "#test' . $i . '" )
            // .keyup(function() {
            // var value = $( this ).val();
            // $( "#' . $component_name . '" ).text( value );
            // })
            // .keyup(); ';
            // echo '</script>';
        }
    }
}

function upload_csv($component_name, $required_status, $box_inp_type, $i)
{ 
    if ($required_status != "Not Required") {
        if ($box_inp_type == "CSV File Upload") {
            $required = "";   $str = "";
            if ($required_status == 'Required') {
                $required = "required";
                $str = "*";
            }
          
          
           ?>
           <div class="col-12 input-group  mb-3">
                <div class="input-group-prepend">

                    <span class="input-group-text" id="inputGroup-sizing-sm">Upload <?php echo $component_name; echo $str; ?> :</span>
                </div>
                <input  type="file" id="<?php echo $component_name;?>" name="<?php echo $component_name;?>" accept=".csv"  <?php echo $required; ?> class="form-control form-control-file"  aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                </div>
           <?php
        }
    }
}

function selection_dropdown($component_name, $required_status, $box_inp_type, $i, $orgunit_id,$camp_id)
{ 
    global $conn;
    if ($required_status != "Not Required") {
        $required = "";
        $str = "";
        
            if ($required_status == 'Required') {
                $required = "required";
                $str = "*";
            }
           
            if ($box_inp_type == "Selection Dropdown") {
               
                    echo ' <div class="input-group input-group-sm  mb-3">';
                    echo ' <div class="input-group-prepend">';
                    echo ' <span class="input-group-text" id="inputGroup-sizing-sm">' . $component_name . $str . ' :</span> ';
                    echo ' </div> ';
           
        
                    echo '<select class="custom-select" id="test' . $i . '" ' . $required . 'name="' . $component_name . '">';
                    echo "<option disabled value=''>Please Select $component_name</option>";
                    if($component_name=='Products') {
                      //echo "<select class='custom-select' >";
                     
                      echo "<option value='all'>All</option>";
                     
                    }
                  
                    
                    echo "</select>";
                  
                    //echo '   <input type="text" id="test' . $i . '" ' . $required . 'name="' . $component_name . '" class="form-control" placeholder="Enter ' . $component_name . '" aria-label="Small" aria-describedby="inputGroup-sizing-sm" required>';
                    echo ' </div> ';
                    // echo ' <script> ';
                    // echo ' $( "#test' . $i . '" )
                    // .keyup(function() {
                    //   var value = $( this ).val();
                    //   $( "#B45" ).text( value );
                    // })
                    // .keyup(); ';
                    // echo '</script>';
                } 
                if ($box_inp_type == "MultiSelection Dropdown")
                {
                     if($camp_id!='')
                     {
                    $stmt= $conn->prepare("SELECT * FROM `campaign_institutes` INNER JOIN organizational_institutes
                     ON campaign_institutes.ou_inst_id= organizational_institutes.ou_inst_id WHERE `CampID`=$camp_id");
                    $stmt->execute();
                    $camp_inst= $stmt->fetchAll();

                    $stmt_ins=$conn->prepare("SELECT registered_institutions.ri_id, organizational_institutes.ou_inst_id as ou_inst_id,institute_name,
                    GROUP_CONCAT( DISTINCT `domain` SEPARATOR ', ' ) as domain FROM`org_institute_maildomain` 
                    INNER JOIN organizational_institutes INNER JOIN registered_institutions ON 
                    organizational_institutes.ou_inst_id = org_institute_maildomain.ou_inst_id AND 
                    registered_institutions.ri_id= organizational_institutes.ri_id WHERE orgunit_id=$orgunit_id  AND
                     organizational_institutes.ou_inst_id NOT IN (SELECT  organizational_institutes.ou_inst_id FROM `campaign_institutes` INNER JOIN organizational_institutes
                     ON campaign_institutes.ou_inst_id= organizational_institutes.ou_inst_id WHERE `CampID`=$camp_id)
                    GROUP BY institute_name
                       ");
                     }
   
                    else
                    {
                     $stmt_ins=$conn->prepare("SELECT registered_institutions.ri_id, organizational_institutes.ou_inst_id,institute_name,
                      GROUP_CONCAT( DISTINCT `domain` SEPARATOR ', ' ) as domain FROM`org_institute_maildomain` 
                      INNER JOIN organizational_institutes INNER JOIN registered_institutions ON 
                      organizational_institutes.ou_inst_id = org_institute_maildomain.ou_inst_id AND 
                      registered_institutions.ri_id= organizational_institutes.ri_id WHERE orgunit_id=$orgunit_id GROUP BY institute_name");
                    }
                        $stmt_ins->execute();
                        $institutions= $stmt_ins->fetchAll();  

                    
                    echo "<div class='multiselect_div'>";

                    if($component_name=='Institutions')
                    { ?>
                       
                     
                        <select name="institutions" class="multiselect multiselect-custom" id="single-selection" >
                        <?php foreach ($institutions as $output) { ?>
                                                        <option value="<?php echo $output['ou_inst_id']; ?>"> <?php echo $output['institute_name']; ?>
                                                        </option>
                                                        
                                                    <?php
                                                    } ?>

                                                  <?php foreach ($camp_inst as $output) { ?>
                                                        <option value="<?php echo $output['ou_inst_id']; ?>"<?php echo ' selected="selected"'; ?>> <?php echo $output['institute_name']; ?>
                                                        </option>
                                                        
                                                    <?php
                                                    } ?>
                                                    

                                                  
                                                </select>


                <?php    }

                    echo 
                    "</div><br>"; ?>
                     <script src="assets/bundles/libscripts.bundle.js"></script>
    <script src="assets/bundles/vendorscripts.bundle.js"></script>

    <script src="assets/vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script> <!-- Bootstrap Colorpicker Js -->
    <script src="assets/vendor/jquery-inputmask/jquery.inputmask.bundle.js"></script> <!-- Input Mask Plugin Js -->
    <script src="assets/vendor/jquery.maskedinput/jquery.maskedinput.min.js"></script>
    <script src="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
    <script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js"></script> <!-- Bootstrap Tags Input Plugin Js -->
    <script src="assets/vendor/nouislider/nouislider.js"></script> <!-- noUISlider Plugin Js -->

    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/bundles/morrisscripts.bundle.js"></script>
    <script src="assets/js/pages/forms/advanced-form-elements.js"></script>

                  
                  


<?php

                }
        
    }
}

function rich_textbox($component_name, $required_status, $box_inp_type, $i,$value)
{ 
    if ($required_status != "Not Required") {
        $required = "";
        $str = "";
            if ($required_status == 'Required') {
                $required = "required";
                $str = "*";
            }
            $values=explode("explode@here",$value);
            if ($box_inp_type == "Rich Text Box") {

            
                echo ' <div class="col-12">';
                echo '<label>Add ' . $component_name . $str . ':</label> <br> ';
              
                echo '  <div class="input-group  mb-3">';
    
                echo ' <div class="input-group-prepend">';
                echo '   <span class="input-group-text" id="inputGroup-sizing-sm">' . $component_name . ' Subject:</span>';
                echo  ' </div>';
                echo '  <input type="text" id="Camp_sub" name="Camp_sub" value="'.$values[1].'" class="form-control" placeholder="Enter ' . $component_name . ' Subject"  > ';
                echo ' </div>';
    
                echo '<div class="input-group  mb-3">';
    
    
                echo '   <textarea type="text" id="ckeditor" ' . $required . ' name="' . $component_name . '" class=" ckeditor form-control" placeholder="Enter ' . $component_name . '" 
                aria-label="Small" aria-describedby="inputGroup-sizing-sm" cols="100"  required> '.$values[0].' </textarea>';
                echo '</div> ';
                echo ' </div>';
    
        //         echo ' <script> ';
               
        //         echo ' CKEDITOR.on("instanceCreated", function(event) {
                   
        //             event.editor.on("change", function () {
        //                 $("#' . $component_name . '").append("Dear Dr. Admin,</br>")
        //         $("#' . $component_name . '").html(event.editor.getData());
        //     });
        // }); ';
        //         echo '</script>';
            }
        
    }
}

function img_upload($component_name, $required_status, $box_inp_type, $i,$value)
{
    if ($required_status != "Not Required") {
        // echo $component_name . " ";
        // echo $required_status . " ";
     $box_inp_type . " <br>";
        $required = "";
        $str = "";
        if ($required_status == 'Required') {
            $required = "required";
            $str = "*";
        }
        
        

        

        if ($box_inp_type == "Image Upload") {

            // initiate show variable if you want to show image value or not . it contains code to have value in dropify.
            // $value is the variable which contains name/value of image ...get from database
            // $component_name is just  a name/label of Input field 

            $show="";
            if(!empty($value)){
                $show=' id="dropify-event" data-default-file="img/'.$component_name.'/'. $value .'" value="img/'.$component_name.'/'. $value .'"';
                $required = "";
            }

            echo '<div class="input-group input-group-sm mb-3">';
            echo '<label>Upload ' . $component_name . $str . ':</label>';

            echo '<input type="file" ' . $required . ' name="'.$component_name.'" '.$show.'   class="dropify" data-height="70" accept="image/*" 
            data-allowed-file-extensions="jpg png jpeg gif">';
            echo '</div>';

            
        }

        
    }
}




?>

  

<script src="assets/bundles/chartist.bundle.js"></script>
<script src="assets/bundles/knob.bundle.js"></script> <!-- Jquery Knob-->
<script src="assets/bundles/flotscripts.bundle.js"></script> <!-- flot charts Plugin Js -->
<script src="assets/vendor/flot-charts/jquery.flot.selection.js"></script>
<script src="assets/bundles/mainscripts.bundle.js"></script>
<script src="assets/js/index.js"></script>


<script src="assets/vendor/jquery-validation/jquery.validate.js"></script> <!-- Jquery Validation Plugin Css -->
<script src="assets/vendor/jquery-steps/jquery.steps.js"></script> <!-- JQuery Steps Plugin Js -->
<script src="assets/js/pages/forms/form-wizard.js"></script>

<script src="assets/js/pages/forms/editors.js"></script>
<!-- <script src="assets/vendor/dropify/js/dropify.min.js"></script>
    <script src="assets/js/pages/forms/dropify.js"></script> -->
<script src="assets/bundles/libscripts.bundle.js"></script>
<script src="assets/bundles/vendorscripts.bundle.js"></script>

<script src="assets/vendor/ckeditor/ckeditor.js"></script> <!-- Ckeditor -->

<!-- script needed to run dropify-->
<script src="assets/vendor/dropify/js/dropify.min.js"></script>
<script src="assets/js/pages/forms/dropify.js"></script>