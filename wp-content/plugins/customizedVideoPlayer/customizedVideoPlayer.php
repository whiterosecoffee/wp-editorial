<?php

/*
  Plugin Name: Customized VideoPlayer
  Plugin URI: http://www.abcdefg.net
  Description: Customized Video Player that takes youtube url, title and caption url file as an input with styling.
  Author: Omer Kalim
  Version: 1.0
  Author URI: http://www.omerkalim.com
 */

function ap_action_init(){
    load_plugin_textdomain('customizedVideoPlayer', false, dirname( plugin_basename( __FILE__ ) ));
}
add_action('init', 'ap_action_init');

function customizedVideoPlayer_display($content) {
    
    $id = get_the_ID();

    $customizedVideoPlayer_data = get_post_meta($id, 'customizedVideoPlayer_data', true);

    if(empty($customizedVideoPlayer_data)){
        return $content;
    }

    $count_vp_array = 1;
    if(!empty($customizedVideoPlayer_data)){
        $count_vp_array = count($customizedVideoPlayer_data);
    }

    $spacer = '<div style="height:20px;"></div>';
    $new_content = '';
    $before_content = '';
    $after_content = '';
    $new_content .= '<script src="http://jwpsrv.com/library/h6ZMbuypEeOSRyIACyaB8g.js"></script>';

    $incrementer = 1;
    foreach ($customizedVideoPlayer_data as $vp_data_array):
//                echo '<pre>';
//                print_r($vp_data_array);
//                echo '</pre>';
//                die();

        if( ($vp_data_array['customizedVideoPlayer_url'] != "") && ($vp_data_array['customizedVideoPlayer_display'] == "Yes") ){

            if($vp_data_array['customizedVideoPlayer_placement'] == "before_content"){

                $before_content .= $spacer;
                $before_content .= '<div id="' . $incrementer . '_customizedVideoPlayer"></div>';

                $before_content .= '<script type="text/javascript">';
                $before_content .= 'jwplayer("' . $incrementer . '_customizedVideoPlayer").setup({
                                    file: "' . $vp_data_array['customizedVideoPlayer_url'] . '",
                                    title: "' . $vp_data_array['customizedVideoPlayer_title'] . '",
                                    width: "100%",
                                    aspectratio: "16:9",
                                    primary: "flash",
                                    tracks: [
                                        {file: "' . $vp_data_array['customizedVideoPlayer_caption'] . '", default: true}
                                    ]
                                });';
                $before_content .= '</script>';
            }else{

                $after_content .= '<div id="' . $incrementer . '_customizedVideoPlayer"></div>';
                if($incrementer < $count_vp_array){
                    $after_content .= $spacer;
                }

                $after_content .= '<script type="text/javascript">';
                $after_content .= 'jwplayer("' . $incrementer . '_customizedVideoPlayer").setup({
                                    file: "' . $vp_data_array['customizedVideoPlayer_url'] . '",
                                    title: "' . $vp_data_array['customizedVideoPlayer_title'] . '",
                                    width: "100%",
                                    aspectratio: "16:9",
                                    primary: "flash",
                                    tracks: [
                                        {file: "' . $vp_data_array['customizedVideoPlayer_caption'] . '", default: true}
                                    ]
                                });';
                $after_content .= '</script>';
            }
        }
        $incrementer++;
    endforeach;

    $content = $new_content . $before_content . $content . $after_content;
    return $content;
}
add_filter('the_content', 'customizedVideoPlayer_display');


function customizedVideoPlayer_meta_box() {
    add_meta_box('customizedVideoPlayer-meta-box', 'Customized Video Player', 'customizedVideoPlayer_meta_box_fields', 'post', 'normal', 'high');
}

function customizedVideoPlayer_meta_box_fields($post) {
    
    $customizedVideoPlayer_data = get_post_meta($post->ID, 'customizedVideoPlayer_data', true);
    
    ?>
    <style type="text/css">
        .customizedVideoPlayer_input{
            width: 100%;
            margin-right: 15px;
        }
        
        .label_td{
            width: 15%;
        }
        .input_td{
            width: 85%;
        }
        .remove_customizedVideoPlayer{
            text-decoration: none;
            color: white;
            width: 15px;
            height: 15px;
            line-height: 13px;
            text-align: center;
            border-radius: 20px;
            float: right;
            background: #CCC;
        }
        .remove_customizedVideoPlayer:hover{
            color: #CCC;
            background: #C00;
        }
    </style>
    <div id="customizedVideoPlayer_input_div">
        <input type="hidden" name="customizedVideoPlayer_box_nonce" value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>" />
        <?php
            $count_vp_array = 1;
            if(!empty($customizedVideoPlayer_data)){
                $count_vp_array = count($customizedVideoPlayer_data);
            }
            
            $temp_data = array();
            $temp_data['customizedVideoPlayer_url'] = "";
            $temp_data['customizedVideoPlayer_title'] = "";
            $temp_data['customizedVideoPlayer_caption'] = "";
            $temp_data['customizedVideoPlayer_placement'] = "";
            $temp_data['customizedVideoPlayer_display'] = "";
            $customizedVideoPlayer_data[] = $temp_data;
            
            $incrementer = 1;
            foreach ($customizedVideoPlayer_data as $vp_data_array):
                
//                echo '<pre>';
//                print_r($vp_data_array);
//                echo '</pre>';
//                die();
        ?>
        
                <table style="width: 100%; box-shadow: 1px 2px 10px gray; padding: 10px; margin-bottom: 10px;">
                    <tr>
                        <td colspan="2">
                            <a class="remove_customizedVideoPlayer" href="">x</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="label_td">Video URL:</td>
                        <td class="input_td">
                            <input type="text" name="<?php echo $incrementer?>_customizedVideoPlayer_url" value="<?php echo $vp_data_array['customizedVideoPlayer_url']; ?>" class="customizedVideoPlayer_input"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="label_td">Video Title:</td>
                        <td class="input_td">
                            <input type="text" name="<?php echo $incrementer?>_customizedVideoPlayer_title" value="<?php echo $vp_data_array['customizedVideoPlayer_title']; ?>" class="customizedVideoPlayer_input"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="label_td">Caption URL:</td>
                        <td class="input_td">
                            <input type="text" name="<?php echo $incrementer?>_customizedVideoPlayer_caption" value="<?php echo $vp_data_array['customizedVideoPlayer_caption']; ?>" class="customizedVideoPlayer_input"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="label_td">Placement: </td>
                        <td class="input_td">
                            <select name="<?php echo $incrementer?>_customizedVideoPlayer_placement" style="width: 100%;">
                                <option value="after_content" <?php echo ($vp_data_array['customizedVideoPlayer_placement'] == "after_content") ? "selected" : ""; ?>>After main Content</option>
                                <option value="before_content" <?php echo ($vp_data_array['customizedVideoPlayer_placement'] == "before_content") ? "selected" : ""; ?>>Before main Content</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="label_td">Video Display:</td>
                        <td class="input_td">
                            <input type="radio" name="<?php echo $incrementer?>_customizedVideoPlayer_display" value="Yes" <?php echo (isset($vp_data_array['customizedVideoPlayer_display']) && $vp_data_array['customizedVideoPlayer_display'] == "Yes") ? "checked" : ""; ?>/>Yes
                            &nbsp;&nbsp;&nbsp;
                            <input type="radio" name="<?php echo $incrementer?>_customizedVideoPlayer_display" value="No" <?php echo (isset($vp_data_array['customizedVideoPlayer_display']) && $vp_data_array['customizedVideoPlayer_display'] == "No") ? "checked" : ""; ?>/>No
                        </td>
                    </tr>
                </table>
        <?php
                $incrementer++;
            endforeach;
        ?>
    </div>
    
    <input id="counter_vp" name="number_of_vp" type="hidden" value="<?php echo $count_vp_array+1; ?>"/>
    <a id="add_customizedVideoPlayer" class="button" style="width: auto; float: right;" href="">Add Another Video</a>
    <br>
    
    <script type="text/javascript">
        jQuery( '#customizedVideoPlayer-meta-box' ).addClass( 'closed' );
    
        jQuery(document).ready(function(){

            

            jQuery("#add_customizedVideoPlayer").click(function(event){
                event.preventDefault();
                var counter_vp = parseInt(jQuery("#counter_vp").val());
                var new_counter_vp = counter_vp+1;
                jQuery("#counter_vp").val(new_counter_vp);
                
                var table_string = '<table style="width: 100%; box-shadow: 1px 2px 10px gray; padding: 10px; margin-bottom: 10px;">';
                    table_string += '<tr>';
                        table_string += '<td colspan="2">';
                            table_string += '<a class="remove_customizedVideoPlayer" href="">x</a>';
                        table_string += '</td>';
                    table_string += '</tr>';
                    table_string += '<tr>';
                        table_string += '<td class="label_td">Video URL:</td>';
                        table_string += '<td class="input_td">';
                        table_string += '<input type="text" name="' + new_counter_vp + '_customizedVideoPlayer_url" value="" class="customizedVideoPlayer_input"/>';
                        table_string += '</td>';
                    table_string += '</tr>';
                    table_string += '<tr>';
                        table_string += '<td class="label_td">Video Title:</td>';
                        table_string += '<td class="input_td">';
                            table_string += '<input type="text" name="' + new_counter_vp + '_customizedVideoPlayer_title" value="" class="customizedVideoPlayer_input"/>';
                        table_string += '</td>';
                    table_string += '</tr>';
                    table_string += '<tr>';
                        table_string += '<td class="label_td">Caption URL:</td>';
                        table_string += '<td class="input_td">';
                            table_string += '<input type="text" name="' + new_counter_vp + '_customizedVideoPlayer_caption" value="" class="customizedVideoPlayer_input"/>';
                        table_string += '</td>';
                    table_string += '</tr>';
                    table_string += '<tr>';
                        table_string += '<td class="label_td">Placement: </td>';
                        table_string += '<td class="input_td">';
                            table_string += '<select name="' + new_counter_vp + '_customizedVideoPlayer_placement" style="width: 100%;">';
                                table_string += '<option value="after_content">After main Content</option>';
                                table_string += '<option value="before_content">Before main Content</option>';
                            table_string += '</select>';
                        table_string += '</td>';
                    table_string += '</tr>';
                    table_string += '<tr>';
                        table_string += '<td class="label_td">Video Display:</td>';
                        table_string += '<td class="input_td">';
                            table_string += '<input type="radio" name="' + new_counter_vp + '_customizedVideoPlayer_display" value="Yes" />Yes';
                            table_string += '&nbsp;&nbsp;&nbsp;';
                            table_string += '<input type="radio" name="' + new_counter_vp + '_customizedVideoPlayer_display" value="No" />No';
                        table_string += '</td>';
                    table_string += '</tr>';
                table_string += '</table>';
                
                jQuery("#customizedVideoPlayer_input_div").append(table_string);
            });
            
            jQuery("#customizedVideoPlayer_input_div").on( "click", '.remove_customizedVideoPlayer', function(e){
                e.preventDefault();
                
                if (confirm("This video block will be removed!") == true) {
                    jQuery(this).parent().parent().parent().parent().remove();
                }
            });
        });
    </script>
    
    <?php
}

function save_customizedVideoPlayer_meta_box($post_id) {

    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
        return;
    }

    $number_of_vp = 1;
    
    if(isset($_POST['number_of_vp'])){
        $number_of_vp = $_POST['number_of_vp'];
    }
    
    $all_vp = array();
    
    for($i=1; $i<=$number_of_vp; $i++){
        
        if( !isset($_POST[$i.'_customizedVideoPlayer_url']) && empty($_POST[$i.'_customizedVideoPlayer_url']) &&
                !isset($_POST[$i.'_customizedVideoPlayer_title']) && empty($_POST[$i.'_customizedVideoPlayer_title']) &&
                !isset($_POST[$i.'_customizedVideoPlayer_caption']) && empty($_POST[$i.'_customizedVideoPlayer_caption']) ){
            continue;
        }
            $customizedVideoPlayer_data = array();
            if( isset( $_POST[$i.'_customizedVideoPlayer_url'] ) )
                $customizedVideoPlayer_data['customizedVideoPlayer_url'] = $_POST[$i.'_customizedVideoPlayer_url'];
            if( isset( $_POST[$i.'_customizedVideoPlayer_title'] ) )
                $customizedVideoPlayer_data['customizedVideoPlayer_title'] = $_POST[$i.'_customizedVideoPlayer_title'];
            if( isset( $_POST[$i.'_customizedVideoPlayer_caption'] ) )
                $customizedVideoPlayer_data['customizedVideoPlayer_caption'] = $_POST[$i.'_customizedVideoPlayer_caption'];
            if( isset( $_POST[$i.'_customizedVideoPlayer_placement'] ) )
                $customizedVideoPlayer_data['customizedVideoPlayer_placement'] = $_POST[$i.'_customizedVideoPlayer_placement'];
            if( isset( $_POST[$i.'_customizedVideoPlayer_display'] ) )
                $customizedVideoPlayer_data['customizedVideoPlayer_display'] = $_POST[$i.'_customizedVideoPlayer_display'];

            $all_vp[] = $customizedVideoPlayer_data;
    }
    
//    echo '<pre>';
//    print_r($all_vp);
//    echo '</pre>';
//    die();
    update_post_meta($post_id, 'customizedVideoPlayer_data', $all_vp);

}

add_action('add_meta_boxes', 'customizedVideoPlayer_meta_box');
add_action('save_post', 'save_customizedVideoPlayer_meta_box');