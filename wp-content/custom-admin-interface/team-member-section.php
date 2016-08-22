<?php
?>
<style type="text/css">
/*    th{
        box-shadow: 0 0 2px #888; 
    }
    td{
        padding-left: 5px;
        box-shadow: 0 0 2px #AAA;
    }*/
    input[type="text"]{
        line-height: 10px;
    }
</style>

<h2><strong>Team Member Section</strong></h2>

<div class="updated" style="margin-left: 0px; display: none;">
    <p id="update_message">
    </p>
</div><br>
<?php   
    $args = array(
            'blog_id'      => $GLOBALS['blog_id'],
            'role'         => '',
            'meta_key'     => '',
            'meta_value'   => '',
            'meta_compare' => '',
            'meta_query'   => array(),
            'include'      => array(),
            'exclude'      => array(),
            'orderby'      => 'login',
            'order'        => 'ASC',
            'offset'       => '',
            'search'       => '',
            'number'       => '',
            'count_total'  => false,
            'fields'       => 'all',
            'who'          => ''
    );
    $blogusers = get_users('');
    $temp = array();
    foreach ($blogusers as $user){
        $temp[] = get_user_meta($user->ID, "team-member-order", true);
    }
    array_multisort($temp, SORT_ASC, $blogusers);
?>
<div id="main_user_listing">
    <table style="width: 98.5%;" class="widefat">
        
        <tbody>
            <thead class="alternate">
                <th>Rank</th>
                <th>Name</th>
                <th>Email</th>
                <th>Social Account</th>
                <th>Title</th>
                <th>Nationality</th>
            </thead>
            <tr><td colspan="6"></td></tr>
            <?php   
            $alternate = 0;
            foreach ($blogusers as $user) {
                if( get_user_meta($user->ID, "is-team-member", true) == "on" ):
            ?>
                <tr id="<?php echo $user->ID; ?>" sort_number="<?php echo $alternate+1; ?>" class="sortable_tr">
                        <td>
                            <label id="rank_label_<?php echo $user->ID;?>">
                                <?php echo get_user_meta($user->ID, "team-member-order", true); ?>
                            </label>
                        </td>
                        <td>
                            <a href="<?php echo 'user-edit.php?user_id='.$user->ID; ?>">
                                <?php echo $user->data->display_name; ?>
                            </a>
                        </td>
                        <td><?php echo $user->data->user_email; ?></td>
                        <td>
                            <?php 
                                if(get_user_meta($user->ID, "social_connect_facebook_id", true)) 
                                    echo "Facebook"; 
                                else if(get_user_meta($user->ID, "social_connect_twitter_id", true)) 
                                    echo "Twitter"; 
                            ?>
                        </td>
                        <td><input name="title" value="<?php echo get_user_meta($user->ID, "title", true); ?>" id="title_<?php echo $user->ID; ?>" type="text" class="input_teammember" maxlength="40"/></td>
                        <td><?php echo get_user_meta($user->ID, "nationality", true); ?></td>
                        <!--<td><input name="nationality" value="<?php echo get_user_meta($user->ID, "nationality", true); ?>" type="text" class="input_teammember"</td>-->
                    </tr>
            <?php
                    $alternate++;
                endif;
            }
        ?>
        </tbody>
    </table>
</div>
<h5>* drag Team Member`s name to change the order.</h5>

<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery( "tbody" ).sortable({
            update: function( event, ui ) {
                var data_arr = [];
                jQuery(".sortable_tr").each(function(index){
                    jQuery(this).attr("sort_number", index+1);
                    
                    var id = jQuery(this).attr("id");
                    jQuery("#rank_label_" + id).html(index+1);
                    
                    var temp_arr = [id, index+1];
                    data_arr.push(temp_arr);
                });
                
                
                jQuery.post(backend_object.ajax_url, {
                    action: "sort_teammember_ajax",
                    data_arr: data_arr
                }, function(data) {
//                        jQuery("#update_message").append(data);
                });
                
                jQuery("#update_message").html("Updated!");
                jQuery("#update_message").parent().fadeIn().delay(500).fadeOut();
            }
        });
        //Commenting out, because this is disabling the selection in FIREFOX
//        jQuery( "tbody" ).disableSelection();
        
        jQuery(".input_teammember").focusout(function(){
            var id = jQuery(this).parent().parent().attr("id");
            var field_name = jQuery(this).attr("name");
            var field_value = jQuery(this).val();
            
            console.log(id + " - " + field_name + " - " + field_value);
            
            jQuery.post(backend_object.ajax_url, {
                action: "field_teammember_ajax",
                id: id,
                field_name: field_name,
                field_value: field_value
            }, function(data) {
                console.log("DATA: " + id + " - " + field_name + " - " + field_value);
//                jQuery("#update_message").append(data);
            jQuery("#update_message").html("Updated!");
            jQuery("#update_message").parent().fadeIn();
            });
            
//            jQuery("#update_message").html("Updated!");
//            jQuery("#update_message").parent().fadeIn().delay(500).fadeOut();
        });
    });
</script>   