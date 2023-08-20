<section id="Mailserver" data-status="Mailserver">
                                                                   
<?php    $stmtsp = $conn->prepare("SELECT * FROM mailservers");
                                   $stmtsp->execute();
                                   $sps = $stmtsp->fetchAll();
                                ?>
                               
                                   
                                    <div class="multiselect_div col-3">
                                        <select id="single-selection" onChange="changems();" name="single_selection1" class="multiselect multiselect-custom">
                                      
<option value="all" selected> All </option>
                                        <?php foreach ($sps as $sp) { ?>

                                              

<option value="<?php echo $sp["mailserverid"]; ?>"> <?php echo $sp["vmname"]; ?> </option>

<?php

} ?>
                                        </select>
                                    </div> 

<br>
<br>
                                    <div id="response">
                                        <!--- display table-->
                                    </div>


</section>

  