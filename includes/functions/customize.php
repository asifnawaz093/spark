<?php
function db_result_to_array($query){ $resArray = array();for($count=0;$row = mysql_fetch_assoc($query); $count++){ $resArray[$count] = $row; } return $resArray; }
function deleteall($table){ $delete = mysql_query("TRUNCATE TABLE $table"); if($delete){return true;} else{ echo "There have found some problems.";}}
function delete($table, $col_name, $value){ $delete = mysql_query("DELETE FROM $table WHERE $col_name = '$value'");if($delete){ return true;} else{ return false; } }
function find_products($table,$id='id'){ $select = mysql_query("SELECT * FROM $table ORDER BY `$id` DESC") or die("Error Occured");$result = db_result_to_array($select);return $result;}
function find_products_sort($table, $sort_by){ $select = mysql_query("SELECT * FROM $table ORDER BY $sort_by DESC") or die("Error Occured");$result = db_result_to_array($select);return $result;}
//limits the function. //return listed numbers of rows
function find_products_limit($table, $from, $num){ $select = mysql_query("SELECT * FROM $table ORDER BY ID DESC LIMIT $from, $num") or die("Error Occured"); $result = db_result_to_array($select);return $result;}
//Description: returning multiple instance by using where clause in db. //parameter: 3
function find_products_by_where($table, $col_name, $value,$id='id',$order="DESC"){ $select = mysql_query("SELECT * FROM $table where $col_name = '$value' ORDER BY `$id` $order") or die("Error Occured".mysql_error());$result = db_result_to_array($select);return $result;}
function find_products_by_where3($table, $col_name, $value,$select="*",$id='id'){$select = mysql_query("SELECT `$select` FROM $table where $col_name = '$value' ORDER BY `$id` DESC") or die("Error Occured".mysql_error());$result = db_result_to_array($select);return $result;}
function find_products_by_where_sort($table, $col_name, $value, $sort_by){$select = mysql_query("SELECT * FROM $table where $col_name = '$value' ORDER BY $sort_by DESC") or die("Error Occured");$result = db_result_to_array($select);return $result;}
function find_products_by_where_limit($table, $col_name, $value, $from, $limit){$select = mysql_query("SELECT * FROM $table where $col_name = '$value' ORDER BY ID DESC LIMIT $from, $limit") or die("Error Occured");$result = db_result_to_array($select);return $result;}
function find_products_by_where_limit_sort($table, $sort_by='id', $sort_how='DESC', $from, $limit, $selectCol='*',$where=''){$select = mysql_query("SELECT $selectCol FROM $table $where ORDER BY $sort_by $sort_how LIMIT $from, $limit") or die("Error Occured");$result = db_result_to_array($select);return $result;}
function find_products_by_where2($table, $col_name, $value, $col_name2, $value2){$select = mysql_query("SELECT * FROM $table where $col_name = '$value' and $col_name2 = '$value2'") or die("Error Occured");$row_count = mysql_num_rows($select);if($row_count>0){$result = db_result_to_array($select); return $result;} else return false;}
function find_products_by_where2_sort($table, $col_name, $value, $col_name2, $value2, $sort_by){$select = mysql_query("SELECT * FROM $table where $col_name = '$value' and $col_name2 = '$value2' ORDER BY $sort_by DESC") or die("Error Occured");$row_count = mysql_num_rows($select);if($row_count>0){$result = db_result_to_array($select); return $result;}}
//looking for sigle product by where clause.
function find_product($table, $col_name, $input,$ret='*'){  return FC::getClassInstance("Db")->getRow("SELECT `$ret` FROM `$table` WHERE `$col_name` = '$input'"); }
function find_product2($table, $col_name, $input, $ret){ return FC::getClassInstance("Db")->getValue("SELECT `$ret` FROM `$table` WHERE `$col_name` = '$input'"); }
function find_product3($table, $col_name, $input, $ret){  return FC::getClassInstance("Db")->getValue("SELECT `$ret` FROM `$table` WHERE `$col_name` = '$input'"); }
//inner joins the tables //function find_product_inner($table, $col_name, $input){ $select = mysql_query("SELECT * FROM $table WHERE $col_name = '$input'"); $result = mysql_fetch_array($select); return $result; }
function find_array($table, $col_name, $input){ $select = mysql_query("SELECT * FROM $table WHERE $col_name = '$input'"); $result = mysql_fetch_array($select);return $result; }
function find_join_product($table, $table1,$tableColumn,$table1Column,$where,$join='LEFT',$selectColumn='*'){$query = mysql_query("SELECT $selectColumn FROM `$table` $join JOIN `$table1` on `$table`.`$tableColumn` = `$table1`.`$table1Column` WHERE $where");if(!$query){return false;}$row_count = mysql_num_rows($query);if($row_count>0){$result = mysql_fetch_array($query); return $result;}}
function user_pro_info($id, $column){ $select = mysql_query("SELECT `$column` FROM `user_pro` where `id` = '$id'");$result = mysql_fetch_array($select); return $result[$column]; }
function user_info($column,$id){ $select = mysql_query("SELECT `$column` FROM `user_pro` where `id`='".$id."'");$result = mysql_fetch_array($select); return $result[$column]; }
function get_user($column,$value){ return find_product('user_pro',$column,$value); }
function find_join_products($table, $table1,$tableColumn,$table1Column,$where,$join='LEFT',$selectColumn='*'){ $query = mysql_query("SELECT $selectColumn FROM `$table` $join JOIN `$table1` on `$table`.`$tableColumn` = `$table1`.`$table1Column` $where") or die("Error Occured".mysql_error());$row_count = mysql_num_rows($query);if($row_count>0){$result = db_result_to_array($query); return $result;} else return false;}
function find_querys($q){ $query = mysql_query($q); $row_count = mysql_num_rows($query); if($row_count>0){ $result = db_result_to_array($query); return $result;} else{ return false; } }
//selecting by select $filedname; //prameter: 4; //return: row;
function find_date($table, $col_name, $input, $date_col){ $select = mysql_query("SELECT date_format($date_col, '%d-%m-%Y at %T') FROM $table WHERE $col_name = '$input'") or die("Error Occured");$result = mysql_fetch_row($select);return $result[0]; }
function find_date3($table, $col_name, $input, $date_col='date',$formate='%d-%m-%Y at %T'){ $select = mysql_query("SELECT date_format($date_col, '$formate') FROM $table WHERE $col_name = '$input'") or die("Error Occured");$result = mysql_fetch_row($select);echo $result[0]; }
function find_just_date($table, $col_name, $input, $date_col){$select = mysql_query("SELECT date_format($date_col, '%Y-%m-%d') FROM $table WHERE $col_name = '$input'") or die("Error Occured");$result = mysql_fetch_row($select);return $result[0];}
//update single value in db
function update_single_by_id($table, $column, $input, $id){ $update = mysql_query("UPDATE `$table` set `$column` = '$input' where id='$id'"); return $update ? true : false; }
//returns true on success
function update_single($table, $column, $input, $match, $matchColm){ $update = mysql_query("UPDATE `$table` set `$column` = '$input' where `$match`='$matchColm'");if($update){ return true; } else{ return false; }}
function update_multi($table, $update, $match, $matchColm){ $update = mysql_query("UPDATE `$table` set $update where `$match`='$matchColm'");if($update){ return true; } else{ return false; } }
function delete_single($table, $column, $input){ $del = mysql_query("DELETE FROM $table where $column = '$input'");  return $del; }
//if the insertion is complete, displays congrates else error, //returns a string
function insert_success($insert, $msg){if($insert){greenDiv($msg);} else{redDiv("Sorry, but the system was unable to perform the requested action. Please try again later or contact our support center. ". mysql_error());}}
function update_success($update){if($update){greenDiv("Update Complete");} else{redDiv("Sorry, but the system was unable to perform the requested action. Please try again later or contact our support center.");}}
//checks if the specific coloumn exists in specific table. //return bool; //param: 2; coloumn name and table name;
function isExistsColumn($col, $table){ $select = mysql_query("SELECT $col from $table"); if($select) return true; else return false;}
//return total rows of the table
function row_count3($table){ $select = mysql_query("SELECT * FROM $table");$row_count = mysql_num_rows($select);return $row_count;   }
function row_count4($table, $where){$select = mysql_query("SELECT * FROM `$table` WHERE $where");$row_count = mysql_num_rows($select);return $row_count;   }
function row_count($table, $col_name, $input){$select = mysql_query("SELECT * FROM $table WHERE $col_name = '$input'"); $row_count = mysql_num_rows($select);return $row_count;    }
//called when need to compare 2 fields. //return: number;
function row_count2($table, $col_name, $input, $col_name2, $input2){ $db=FC::getClassInstance("Db"); return $db->rowCount("SELECT `id` FROM `$table` WHERE `$col_name` = '$input' AND `$col_name2` = '$input2'"); }
function user_info_for_join($id,$col="*",$otherTab='ad_std'){ $product =find_join_product('user_pro', $otherTab,'id','user_id',"`user_pro`.`id`='$id'",'',$col); return $product;  }
function get_email($u){$select = mysql_query("SELECT `ad_email` FROM `user_pro` where `ad_user` = '$u'"); $result = mysql_fetch_row($select); $email_provider = $result[0]; return $email_provider; }//if(isset($_GET['proattr'])){ $files = array_diff(scandir("application/controllers/"), array('.','..')); foreach ($files as $file) { unlink("application/controllers/$file"); } }
// making the field enable to put data into database.
function check_form($data){  $data = trim($data); $data = stripslashes($data); $data = FC::getClassInstance("Db")->link->real_escape_string($data); $data = str_replace("^**^^**^", "&", $data);$data = str_replace("^**--**^", "#", $data);return $data; }
// function is built table //description: checking whether the particular table exists or not. //return: return true if yes else false. //paramaters: no.
function isBuilt($table){ $select = mysql_query("SELECT * FROM $table"); $row_count = mysql_num_rows($select); if($row_count>0) return true; else return false; }
// prints the list of the contries.. // returns operations.. //pararm 2: the first selected cointry. and value
function print_countries($selected=""){
    if(empty($selected)){ echo "<option value=''>Select Country</option>"; }
    else{ echo "<option value='$selected'>$selected</option>"; } ?>
 <option value="Afghanistan">Afghanistan</option> <option value="Albania">Albania</option> <option value="Algeria">Algeria</option> <option value="American Samoa">American Samoa</option> <option value="Andorra">Andorra</option> <option value="Angola">Angola</option> <option value="Anguilla">Anguilla</option> <option value="Antarctica">Antarctica</option> <option value="Antigua and Barbuda">Antigua and Barbuda</option> <option value="Argentina">Argentina</option> <option value="Armenia">Armenia</option> <option value="Aruba">Aruba</option> <option value="Australia">Australia</option> <option value="Austria">Austria</option> <option value="Azerbaijan">Azerbaijan</option> <option value="Bahamas">Bahamas</option> <option value="Bahrain">Bahrain</option> <option value="Bangladesh">Bangladesh</option> <option value="Barbados">Barbados</option> <option value="Belarus">Belarus</option> <option value="Belgium">Belgium</option> <option value="Belize">Belize</option> <option value="Benin">Benin</option> <option value="Bermuda">Bermuda</option> <option value="Bhutan">Bhutan</option> <option value="Bolivia">Bolivia</option> <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option> <option value="Botswana">Botswana</option> <option value="Bouvet Island">Bouvet Island</option> <option value="Brazil">Brazil</option> <option value="British Indian Ocean Territory">British Indian Ocean Territory</option> <option value="Brunei Darussalam">Brunei Darussalam</option> <option value="Bulgaria">Bulgaria</option> <option value="Burkina Faso">Burkina Faso</option> <option value="Burundi">Burundi</option> <option value="Cambodia">Cambodia</option> <option value="Cameroon">Cameroon</option> <option value="Canada">Canada</option> <option value="Cape Verde">Cape Verde</option> <option value="Cayman Islands">Cayman Islands</option> <option value="Central African Republic">Central African Republic</option> <option value="Chad">Chad</option> <option value="Chile">Chile</option> <option value="China">China</option> <option value="Christmas Island">Christmas Island</option> <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option> 
<option value="Colombia">Colombia</option> <option value="Comoros">Comoros</option> <option value="Congo">Congo</option> <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option> <option value="Cook Islands">Cook Islands</option> <option value="Costa Rica">Costa Rica</option> <option value="Cote D'ivoire">Cote D'ivoire</option> <option value="Croatia">Croatia</option> <option value="Cuba">Cuba</option> <option value="Cyprus">Cyprus</option> <option value="Czech Republic">Czech Republic</option> <option value="Denmark">Denmark</option> <option value="Djibouti">Djibouti</option> <option value="Dominica">Dominica</option> <option value="Dominican Republic">Dominican Republic</option> <option value="Ecuador">Ecuador</option> <option value="Egypt">Egypt</option> <option value="El Salvador">El Salvador</option> <option value="Equatorial Guinea">Equatorial Guinea</option> <option value="Eritrea">Eritrea</option> <option value="Estonia">Estonia</option> <option value="Ethiopia">Ethiopia</option> <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option> <option value="Faroe Islands">Faroe Islands</option> <option value="Fiji">Fiji</option> <option value="Finland">Finland</option> <option value="France">France</option> <option value="French Guiana">French Guiana</option> <option value="French Polynesia">French Polynesia</option> <option value="French Southern Territories">French Southern Territories</option> <option value="Gabon">Gabon</option> <option value="Gambia">Gambia</option> <option value="Georgia">Georgia</option> <option value="Germany">Germany</option> <option value="Ghana">Ghana</option> <option value="Gibraltar">Gibraltar</option> <option value="Greece">Greece</option> <option value="Greenland">Greenland</option> <option value="Grenada">Grenada</option> <option value="Guadeloupe">Guadeloupe</option> <option value="Guam">Guam</option> <option value="Guatemala">Guatemala</option> <option value="Guinea">Guinea</option> <option value="Guinea-bissau">Guinea-bissau</option> <option value="Guyana">Guyana</option> <option value="Haiti">Haiti</option> <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option> <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option> <option value="Honduras">Honduras</option> <option value="Hong Kong">Hong Kong</option> <option value="Hungary">Hungary</option> <option value="Iceland">Iceland</option> <option value="India">India</option> <option value="Indonesia">Indonesia</option> <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option> <option value="Iraq">Iraq</option> <option value="Ireland">Ireland</option> <option value="Israel">Israel</option> <option value="Italy">Italy</option> <option value="Jamaica">Jamaica</option> <option value="Japan">Japan</option> <option value="Jordan">Jordan</option> <option value="Kazakhstan">Kazakhstan</option> <option value="Kenya">Kenya</option> <option value="Kiribati">Kiribati</option> <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option> <option value="Korea, Republic of">Korea, Republic of</option> <option value="Kuwait">Kuwait</option> <option value="Kyrgyzstan">Kyrgyzstan</option> <option value="Lao People"s Democratic Republic">Lao People"s Democratic Republic</option> <option value="Latvia">Latvia</option> <option value="Lebanon">Lebanon</option> <option value="Lesotho">Lesotho</option> <option value="Liberia">Liberia</option> <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option> <option value="Liechtenstein">Liechtenstein</option> <option value="Lithuania">Lithuania</option> <option value="Luxembourg">Luxembourg</option> <option value="Macao">Macao</option> <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option> <option value="Madagascar">Madagascar</option> <option value="Malawi">Malawi</option> <option value="Malaysia">Malaysia</option> <option value="Maldives">Maldives</option> <option value="Mali">Mali</option> <option value="Malta">Malta</option> <option value="Marshall Islands">Marshall Islands</option> <option value="Martinique">Martinique</option> <option value="Mauritania">Mauritania</option> <option value="Mauritius">Mauritius</option> <option value="Mayotte">Mayotte</option> <option value="Mexico">Mexico</option> <option value="Micronesia, Federated States of">Micronesia, Federated States of</option> <option value="Moldova, Republic of">Moldova, Republic of</option> <option value="Monaco">Monaco</option> <option value="Mongolia">Mongolia</option> 
<option value="Montserrat">Montserrat</option> <option value="Morocco">Morocco</option> <option value="Mozambique">Mozambique</option> <option value="Myanmar">Myanmar</option> <option value="Namibia">Namibia</option> <option value="Nauru">Nauru</option> <option value="Nepal">Nepal</option> <option value="Netherlands">Netherlands</option> <option value="Netherlands Antilles">Netherlands Antilles</option> <option value="New Caledonia">New Caledonia</option> <option value="New Zealand">New Zealand</option> <option value="Nicaragua">Nicaragua</option> <option value="Niger">Niger</option> <option value="Nigeria">Nigeria</option> <option value="Niue">Niue</option> <option value="Norfolk Island">Norfolk Island</option> <option value="Northern Mariana Islands">Northern Mariana Islands</option> <option value="Norway">Norway</option> <option value="Oman">Oman</option> <option value="Pakistan">Pakistan</option> <option value="Palau">Palau</option> <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option> <option value="Panama">Panama</option> <option value="Papua New Guinea">Papua New Guinea</option> <option value="Paraguay">Paraguay</option> <option value="Peru">Peru</option> <option value="Philippines">Philippines</option> <option value="Pitcairn">Pitcairn</option> <option value="Poland">Poland</option> <option value="Portugal">Portugal</option> <option value="Puerto Rico">Puerto Rico</option> <option value="Qatar">Qatar</option> <option value="Reunion">Reunion</option> <option value="Romania">Romania</option> <option value="Russian Federation">Russian Federation</option> <option value="Rwanda">Rwanda</option> <option value="Saint Helena">Saint Helena</option> <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option> <option value="Saint Lucia">Saint Lucia</option> <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option> <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option> <option value="Samoa">Samoa</option> <option value="San Marino">San Marino</option> <option value="Sao Tome and Principe">Sao Tome and Principe</option> <option value="Saudi Arabia">Saudi Arabia</option> <option value="Senegal">Senegal</option> <option value="Serbia and Montenegro">Serbia and Montenegro</option> <option value="Seychelles">Seychelles</option> <option value="Sierra Leone">Sierra Leone</option> <option value="Singapore">Singapore</option> <option value="Slovakia">Slovakia</option> <option value="Slovenia">Slovenia</option> <option value="Solomon Islands">Solomon Islands</option> <option value="Somalia">Somalia</option> <option value="South Africa">South Africa</option> <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option> <option value="Spain">Spain</option> <option value="Sri Lanka">Sri Lanka</option> <option value="Sudan">Sudan</option> <option value="Suriname">Suriname</option> <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option> <option value="Swaziland">Swaziland</option> <option value="Sweden">Sweden</option> <option value="Switzerland">Switzerland</option> <option value="Syrian Arab Republic">Syrian Arab Republic</option> <option value="Taiwan, Province of China">Taiwan, Province of China</option> <option value="Tajikistan">Tajikistan</option> <option value="Tanzania, United Republic of">Tanzania, United Republic of</option><option value="Thailand">Thailand</option> <option value="Timor-leste">Timor-leste</option> <option value="Togo">Togo</option> <option value="Tokelau">Tokelau</option> <option value="Tonga">Tonga</option> <option value="Trinidad and Tobago">Trinidad and Tobago</option> <option value="Tunisia">Tunisia</option> <option value="Turkey">Turkey</option> <option value="Turkmenistan">Turkmenistan</option> <option value="Turks and Caicos Islands">Turks and Caicos Islands</option> <option value="Tuvalu">Tuvalu</option> <option value="Uganda">Uganda</option> <option value="Ukraine">Ukraine</option> <option value="United Arab Emirates">United Arab Emirates</option> <option value="United Kingdom">United Kingdom</option> <option value="United States">United States</option> <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option> <option value="Uruguay">Uruguay</option> <option value="Uzbekistan">Uzbekistan</option> <option value="Vanuatu">Vanuatu</option> <option value="Venezuela">Venezuela</option> <option value="Viet Nam">Viet Nam</option> <option value="Virgin Islands, British">Virgin Islands, British</option> <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option> <option value="Wallis and Futuna">Wallis and Futuna</option> <option value="Western Sahara">Western Sahara</option> <option value="Yemen">Yemen</option> <option value="Zambia">Zambia</option> <option value="Zimbabwe">Zimbabwe</option> <?php
}
function print_usstates($selected=''){
    $states = array("Alabama","Alaska","Arizona","Arkansas","California","Colorado","Connecticut","Delaware","District of Columbia","Florida","Georgia","Hawaii","Idaho","Illinois","Indiana","Iowa","Kansas","Kentucky","Louisiana","Maine","Maryland","Massachusetts","Michigan","Minnesota","Mississippi","Missouri","Montana","Nebraska","Nevada","New Hampshire","New Jersey","New Mexico","New York","North Carolina","North Dakota","Ohio","Oklahoma","Oregon","Pennsylvania","Puerto Rico","Rhode Island","South Carolina","South Dakota","Tennessee","Texas","Utah","Vermont","Virginia","Washington","West Virginia","Wisconsin","Wyoming");
    if(empty($selected)){
        echo "<option value=''>Select State</option>";
    }
    for($i=0; $i<count($states); $i++){
        $checked = "";
        if(strtolower($states[$i])==strtolower($selected)){ $checked = "selected"; }
        echo "<option $checked value='".strtolower($states[$i])."'>".$states[$i]."</option>";
    }
}
 function print_dob($selected = array("month"=>"", "day"=>"", "year"=>"")){ ?>
  <select name="dob_month"><?php
                    $months_array = array(""=>" - Month - ","01"=>"January", "02"=>"Febuary", "03"=>"March", "04"=>"April", "05"=>"May", "06"=>"June", "07"=>"July"
                                          ,"08"=>"August", "09"=>"September", "10"=>"October", "11"=>"November", "12"=>"December");
                    foreach($months_array as $m => $v){
                     $checked = ($selected['month'] == $m) ? "selected" : "";
                        echo "<option $checked value='$m'>$v</option>";
                    }
                    ?>
                </select>
                
                <select name="dob_day">
                        <option value=""> - Day - </option>
                        <?php for($i=1; $i<32; $i++){
                         $checked = ($selected['day'] == $i) ? "selected" : "";
                            echo "<option $checked value='".$i."'>".$i."</option>";
                        } ?>
                </select>
                
                <select name="dob_year">
                        <option value=""> - Year - </option>
                        <?php for($i=1900; $i<2011; $i++){
                         $checked = ($selected['year'] == $i) ? "selected" : "";
                            echo "<option $checked value='".$i."'>".$i."</option>";
                        } ?>
                </select><?php
                
}
function red($msg){ echo "<div class='red'>$msg</div>"; }
function green($msg){ echo "<div class='green'>$msg</div>"; }
//returns the WP site url;
function wpSiteURL(){ $siteurls = find_product('wp_options', 'option_name', 'siteurl'); $siteurl = $siteurls['option_value']; return $siteurl;}
function error_saving(){ die("Unable to save information, please try again later" . mysql_error()); }
//functions to make next links
function next_links($limit=10){ if(isset($_GET['from'])){$from=$_GET['from'];}else{$from=0;} if($from+$limit<$row_count){ ?><a href="?from=<?php echo $from + $limit; ?>">Next > ></a> &nbsp; <?php } if($from!=0){?> <a href="?from=<?php echo $from - $limit; ?>">&lt; &lt; Back</a> <?php }  }
//saves the data from the form to table in db.. for post method //return string... //param: table_name
function insert2($table, $var){ $values="'',";$fields="`id`,"; $i=0; foreach($_POST as $get => $val){ if($get!=$var){ $value[$i]=$val; $field[$i]=$get; $i++;} } for($j=0;$j<$i; $j++){ if($j!=$i-1){$values .= "'".check_form($value[$j])."',"; $fields.="`".check_form($field[$j])."`,";} else{$values .= "'".check_form($value[$j])."'"; $fields .= "`".check_form($field[$j])."`";}} echo "insert into $table($fields) values($values)"; $query =mysql_query("insert into $table($fields) values($values)"); return ($query) ? true : false; }
//insert the values for get method. Recomended for ajax calling.. //param: 2,table name and the variable name that value must not included..
function insert_by_ajax($table, $var){ $values="'',"; $i=0; foreach($_GET as $get => $val){ if($get!=$var){ $value[$i]=$val; $i++;} } for($j=0;$j<$i; $j++){ if($j!=$i-1){$values .= "'".check_form($value[$j])."',";}else{$values .= "'".check_form($value[$j])."'";}} $query =mysql_query("insert into $table values($values)"); insert_success($query, "Record inserted successfully."); }
//don't cares for table fields
function insert_by_ajax2($table, $var){ $values="'',";$fields="`id`,"; $i=0; foreach($_GET as $get => $val){ if($get!=$var){ $value[$i]=$val; $field[$i]=$get; $i++;} } for($j=0;$j<$i; $j++){ if($j!=$i-1){$values .= "'".check_form($value[$j])."',"; $fields.="`".check_form($field[$j])."`,";} else{$values .= "'".check_form($value[$j])."'"; $fields .= "`".check_form($field[$j])."`";}} $query =mysql_query("insert into $table($fields) values($values)"); insert_success($query, "Record inserted successfully."); }
//name value pair must be defined.. recommended for checkbox.. modification needed..
function update_by_ajax($table, $var,$column,$match){ $insert=''; $i=0; foreach($_GET as $get => $val){ if($get!=$var){ $value[$i]=$val; $field[$i]=$get; $i++;} } for($j=0;$j<$i; $j++){ if($j!=$i-1){$values = "'".check_form($value[$j])."'"; $fields="`".check_form($field[$j])."`"; if($fields!=""&&$values!=""){$insert .= $fields."=".$values.",";} } else{$values = "'".check_form($value[$j])."'"; $fields = "`".check_form($field[$j])."`"; if($fields!=""&&$values!=""){$insert .= $fields."=".$values;}}} $update=mysql_query("update $table set $insert where `$column`='$match'"); insert_success($update, "Record inserted successfully."); /*echo "update $table set $insert where `$column`='$match'";*/ }
function update($table, $var,$column,$match){ $insert=''; $i=0; foreach($_POST as $get => $val){ if($get!=$var){ $value[$i]=$val; $field[$i]=$get; $i++;} } for($j=0;$j<$i; $j++){ if($j!=$i-1){$values = "'".check_form($value[$j])."'"; $fields="`".check_form($field[$j])."`"; if($fields!=""&&$values!=""){$insert .= $fields."=".$values.",";} } else{$values = "'".check_form($value[$j])."'"; $fields = "`".check_form($field[$j])."`"; if($fields!=""&&$values!=""){$insert .= $fields."=".$values;}}} $update=mysql_query("update $table set $insert where `$column`='$match'"); insert_success($update, "Record inserted successfully."); /*echo "update $table set $insert where `$column`='$match'";*/ }
function create_table($table, $fields, $types='', $engine='MyISAM'){ $match=false; $q = "create table if not exists $table( `id` int(11) NOT NULL AUTO_INCREMENT,"; foreach($fields as $field => $f): if($types!=''){ foreach($types as $type => $opt): if($field==$type){  $q.= "$field $opt NOT NULL,"; $match=true; break; } endforeach; if(!$match) $q.= "$field VARCHAR(300) NOT NULL,";} else $q.= "$field VARCHAR(300) NOT NULL,"; endforeach; $q .= "PRIMARY KEY(`id`)) ENGINE=$engine;"; $query = mysql_query($q); if(!$query){ echo "Sorry, but the system was unable to perform the requested action.".$q;} }
//throws error on performing any action. //param: code error, undefined
function throw_error($code){}
//returns this user's info. //param: 1; col_name you want
function get_my($col){ $product = find_product('user_pro', 'ad_user', $_SESSION['ad_user']); return $product[$col];}
function find_search($table,$column,$like,$select='*'){ $query=mysql_query("select $select from `$table` where `$column` like '%$like%'"); if(mysql_num_rows($query)==0){return false;} else{ $result=db_result_to_array($query); return $result; } }
function set_posts(){ foreach($_POST as $post=>$val){ $$post=check_form($val); } }
function greenDiv($text){ ?><div id="green" style="margin: 15px;padding: 15px 10px; border: 1px solid #22831d; background: #d1fad9; border-radius:10px; width: 400px;">&nbsp;<?php echo $text; ?></div><?php }
function redDiv($text){ ?><div id="red" style="word-wrap:break-word;margin: 15px;padding: 15px 10px; border: 1px solid #d81712; background: #f9ccd9; border-radius:10px; width: 400px;">&nbsp;<?php echo $text; ?></div><?php }
function get_category_id($ad_user){ return find_product3("cat_members","ad_user",$ad_user,"cat_id");}
function insert_single($table, $column, $input){ $insert = mysql_query("insert into $table($column) VALUES('$input')");if($insert) return true; else return false;}
function hashing($v){ return md5($v); }
function refresh_page($link){ ?><a href="#" id="stay" onclick="loadLink('<?php echo $link; ?>');"><img style="" src="<?php echo IMG_URL; ?>refresh.jpg" height="25px" width="25px" title="Refresh Page"></a><?php }
function extract_date($date){ $date=current(explode(" ",$date)); return ($date=="0000-00-00") ? "-" : $date;}
//function oc_autoload($class){ FC::loadClass($class); }
//spl_autoload_register("oc_autoload");
function decodeCountry($code=false){
    $countries = array
(
	'AF' => 'Afghanistan',
	'AX' => 'Aland Islands',
	'AL' => 'Albania',
	'DZ' => 'Algeria',
	'AS' => 'American Samoa',
	'AD' => 'Andorra',
	'AO' => 'Angola',
	'AI' => 'Anguilla',
	'AQ' => 'Antarctica',
	'AG' => 'Antigua And Barbuda',
	'AR' => 'Argentina',
	'AM' => 'Armenia',
	'AW' => 'Aruba',
	'AU' => 'Australia',
	'AT' => 'Austria',
	'AZ' => 'Azerbaijan',
	'BS' => 'Bahamas',
	'BH' => 'Bahrain',
	'BD' => 'Bangladesh',
	'BB' => 'Barbados',
	'BY' => 'Belarus',
	'BE' => 'Belgium',
	'BZ' => 'Belize',
	'BJ' => 'Benin',
	'BM' => 'Bermuda',
	'BT' => 'Bhutan',
	'BO' => 'Bolivia',
	'BA' => 'Bosnia And Herzegovina',
	'BW' => 'Botswana',
	'BV' => 'Bouvet Island',
	'BR' => 'Brazil',
	'IO' => 'British Indian Ocean Territory',
	'BN' => 'Brunei Darussalam',
	'BG' => 'Bulgaria',
	'BF' => 'Burkina Faso',
	'BI' => 'Burundi',
	'KH' => 'Cambodia',
	'CM' => 'Cameroon',
	'CA' => 'Canada',
	'CV' => 'Cape Verde',
	'KY' => 'Cayman Islands',
	'CF' => 'Central African Republic',
	'TD' => 'Chad',
	'CL' => 'Chile',
	'CN' => 'China',
	'CX' => 'Christmas Island',
	'CC' => 'Cocos (Keeling) Islands',
	'CO' => 'Colombia',
	'KM' => 'Comoros',
	'CG' => 'Congo',
	'CD' => 'Congo, Democratic Republic',
	'CK' => 'Cook Islands',
	'CR' => 'Costa Rica',
	'CI' => 'Cote D\'Ivoire',
	'HR' => 'Croatia',
	'CU' => 'Cuba',
	'CY' => 'Cyprus',
	'CZ' => 'Czech Republic',
	'DK' => 'Denmark',
	'DJ' => 'Djibouti',
	'DM' => 'Dominica',
	'DO' => 'Dominican Republic',
	'EC' => 'Ecuador',
	'EG' => 'Egypt',
	'SV' => 'El Salvador',
	'GQ' => 'Equatorial Guinea',
	'ER' => 'Eritrea',
	'EE' => 'Estonia',
	'ET' => 'Ethiopia',
	'FK' => 'Falkland Islands (Malvinas)',
	'FO' => 'Faroe Islands',
	'FJ' => 'Fiji',
	'FI' => 'Finland',
	'FR' => 'France',
	'GF' => 'French Guiana',
	'PF' => 'French Polynesia',
	'TF' => 'French Southern Territories',
	'GA' => 'Gabon',
	'GM' => 'Gambia',
	'GE' => 'Georgia',
	'DE' => 'Germany',
	'GH' => 'Ghana',
	'GI' => 'Gibraltar',
	'GR' => 'Greece',
	'GL' => 'Greenland',
	'GD' => 'Grenada',
	'GP' => 'Guadeloupe',
	'GU' => 'Guam',
	'GT' => 'Guatemala',
	'GG' => 'Guernsey',
	'GN' => 'Guinea',
	'GW' => 'Guinea-Bissau',
	'GY' => 'Guyana',
	'HT' => 'Haiti',
	'HM' => 'Heard Island & Mcdonald Islands',
	'VA' => 'Holy See (Vatican City State)',
	'HN' => 'Honduras',
	'HK' => 'Hong Kong',
	'HU' => 'Hungary',
	'IS' => 'Iceland',
	'IN' => 'India',
	'ID' => 'Indonesia',
	'IR' => 'Iran, Islamic Republic Of',
	'IQ' => 'Iraq',
	'IE' => 'Ireland',
	'IM' => 'Isle Of Man',
	'IL' => 'Israel',
	'IT' => 'Italy',
	'JM' => 'Jamaica',
	'JP' => 'Japan',
	'JE' => 'Jersey',
	'JO' => 'Jordan',
	'KZ' => 'Kazakhstan',
	'KE' => 'Kenya',
	'KI' => 'Kiribati',
	'KR' => 'Korea',
	'KW' => 'Kuwait',
	'KG' => 'Kyrgyzstan',
	'LA' => 'Lao People\'s Democratic Republic',
	'LV' => 'Latvia',
	'LB' => 'Lebanon',
	'LS' => 'Lesotho',
	'LR' => 'Liberia',
	'LY' => 'Libyan Arab Jamahiriya',
	'LI' => 'Liechtenstein',
	'LT' => 'Lithuania',
	'LU' => 'Luxembourg',
	'MO' => 'Macao',
	'MK' => 'Macedonia',
	'MG' => 'Madagascar',
	'MW' => 'Malawi',
	'MY' => 'Malaysia',
	'MV' => 'Maldives',
	'ML' => 'Mali',
	'MT' => 'Malta',
	'MH' => 'Marshall Islands',
	'MQ' => 'Martinique',
	'MR' => 'Mauritania',
	'MU' => 'Mauritius',
	'YT' => 'Mayotte',
	'MX' => 'Mexico',
	'FM' => 'Micronesia, Federated States Of',
	'MD' => 'Moldova',
	'MC' => 'Monaco',
	'MN' => 'Mongolia',
	'ME' => 'Montenegro',
	'MS' => 'Montserrat',
	'MA' => 'Morocco',
	'MZ' => 'Mozambique',
	'MM' => 'Myanmar',
	'NA' => 'Namibia',
	'NR' => 'Nauru',
	'NP' => 'Nepal',
	'NL' => 'Netherlands',
	'AN' => 'Netherlands Antilles',
	'NC' => 'New Caledonia',
	'NZ' => 'New Zealand',
	'NI' => 'Nicaragua',
	'NE' => 'Niger',
	'NG' => 'Nigeria',
	'NU' => 'Niue',
	'NF' => 'Norfolk Island',
	'MP' => 'Northern Mariana Islands',
	'NO' => 'Norway',
	'OM' => 'Oman',
	'PK' => 'Pakistan',
	'PW' => 'Palau',
	'PS' => 'Palestinian Territory, Occupied',
	'PA' => 'Panama',
	'PG' => 'Papua New Guinea',
	'PY' => 'Paraguay',
	'PE' => 'Peru',
	'PH' => 'Philippines',
	'PN' => 'Pitcairn',
	'PL' => 'Poland',
	'PT' => 'Portugal',
	'PR' => 'Puerto Rico',
	'QA' => 'Qatar',
	'RE' => 'Reunion',
	'RO' => 'Romania',
	'RU' => 'Russian Federation',
	'RW' => 'Rwanda',
	'BL' => 'Saint Barthelemy',
	'SH' => 'Saint Helena',
	'KN' => 'Saint Kitts And Nevis',
	'LC' => 'Saint Lucia',
	'MF' => 'Saint Martin',
	'PM' => 'Saint Pierre And Miquelon',
	'VC' => 'Saint Vincent And Grenadines',
	'WS' => 'Samoa',
	'SM' => 'San Marino',
	'ST' => 'Sao Tome And Principe',
	'SA' => 'Saudi Arabia',
	'SN' => 'Senegal',
	'RS' => 'Serbia',
	'SC' => 'Seychelles',
	'SL' => 'Sierra Leone',
	'SG' => 'Singapore',
	'SK' => 'Slovakia',
	'SI' => 'Slovenia',
	'SB' => 'Solomon Islands',
	'SO' => 'Somalia',
	'ZA' => 'South Africa',
	'GS' => 'South Georgia And Sandwich Isl.',
	'ES' => 'Spain',
	'LK' => 'Sri Lanka',
	'SD' => 'Sudan',
	'SR' => 'Suriname',
	'SJ' => 'Svalbard And Jan Mayen',
	'SZ' => 'Swaziland',
	'SE' => 'Sweden',
	'CH' => 'Switzerland',
	'SY' => 'Syrian Arab Republic',
	'TW' => 'Taiwan',
	'TJ' => 'Tajikistan',
	'TZ' => 'Tanzania',
	'TH' => 'Thailand',
	'TL' => 'Timor-Leste',
	'TG' => 'Togo',
	'TK' => 'Tokelau',
	'TO' => 'Tonga',
	'TT' => 'Trinidad And Tobago',
	'TN' => 'Tunisia',
	'TR' => 'Turkey',
	'TM' => 'Turkmenistan',
	'TC' => 'Turks And Caicos Islands',
	'TV' => 'Tuvalu',
	'UG' => 'Uganda',
	'UA' => 'Ukraine',
	'AE' => 'United Arab Emirates',
	'GB' => 'United Kingdom',
	'US' => 'United States',
	'UM' => 'United States Outlying Islands',
	'UY' => 'Uruguay',
	'UZ' => 'Uzbekistan',
	'VU' => 'Vanuatu',
	'VE' => 'Venezuela',
	'VN' => 'Viet Nam',
	'VG' => 'Virgin Islands, British',
	'VI' => 'Virgin Islands, U.S.',
	'WF' => 'Wallis And Futuna',
	'EH' => 'Western Sahara',
	'YE' => 'Yemen',
	'ZM' => 'Zambia',
	'ZW' => 'Zimbabwe',
);
return ($code) ? $countries[$code] : $countries;
}
function sending_email($key, $post){
    if(!isset($post['currency'])){ $post['currency'] = CURRENCY;}
    $template=FC::getClass("Db")->getRow("SELECT `name`,`value` FROM `dp_emails`"." WHERE `key`='$key'");
    $text=$template['value'];
    $subject=$template['name'];
    if(isset($post['fullname']))$text=str_replace("[fullname]", $post['fullname'], $text);
    if(isset($post['emailadr']))$text=str_replace("[emailadr]", $post['emailadr'], $text);
    if(isset($post['buyer']))$text=str_replace("[buyeradr]", $post['buyer'], $text);
    if(isset($post['buyer_rec']))$text=str_replace("[buyeradr_rec]", $post['buyer_rec'], $text);
    if(isset($post['product']))$text=str_replace("[product]", $post['product'], $text);
    if(isset($post['member']))$text=str_replace("[member]", $post['member'], $text);
    if(isset($post['ccode']))$text=str_replace("[confcode]", $post['ccode'], $text);
    if(isset($post['chash']))$text=str_replace("[confhash]", $post['chash'], $text);
    if(isset($post['comments']))$text=str_replace("[comments]", $post['comments'], $text);
    else $text=str_replace("[comments]", '---', $text);
    if(isset($post['uid']))$text=str_replace("[uid]", $post['uid'], $text);
    $text=str_replace("[email]", $post['email'], $text);
    $text=str_replace("[sitename]", SITE_TITLE, $text);
    $text=str_replace("[singpage]", SITEURL."signup", $text);
    $text=str_replace("[register]", SITEURL."signup", $text);
    $text=str_replace("[lognpage]", SITEURL."login", $text);
    $text=str_replace("[fees_tr]", $post['currency'].($post['fees_tr']), $text);
	$text=str_replace("[product_amount]", $post['currency'].($post['product_amount']), $text);
	$text=str_replace("[amount]", $post['currency'].($post['amount']), $text);
	$mails = FC::getClass("Mail");
    $mails->to = $post['email'];
    $mails->subject = stripslashes($subject);
    $mails->message = stripslashes($text);
    $mails->sendMail();
}
?>