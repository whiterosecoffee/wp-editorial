<?php

echo "<h2>Related Articles</h2>";
    
if (isset($_POST['relatedarticles_hidden']) && $_POST['relatedarticles_hidden'] == 'Y') {
    $relatedarticles_taxonomies = $_POST['relatedarticles_taxonomies'];
    update_option('relatedarticles_taxonomies', $relatedarticles_taxonomies);
//    echo "<pre>";
//    print_r($relatedarticles_taxonomies);
//    echo "</pre>";
    ?>
    <div class="updated">
        <p>
            <strong><?php _e('Option saved.'); ?></strong>
        </p>
    </div>
    <?php
} else {
    //Normal page display
    $relatedarticles_taxonomies = get_option('relatedarticles_taxonomies');
}
?>

<style>
    input{
        width: 100%;
    }
    select{
        width: 100%;
        height: 200px !important;
    }
    label{
        color: gray;
        font-size: 12px;
    }
</style>
<div class="wrap">
    <br>
    <?php 
        echo "<h3>" . __('Taxonomy') . "</h3>"; 
        
        $args = array(
          'public'   => true,
	  'show_ui'   => true
        ); 
        $taxonomies = get_taxonomies($args);
        
//        echo "<pre>";
//        print_r($taxonomies);
//        echo "</pre>";
    ?>
    <hr>
    <form name="relatedarticles_form" method="post" action="">
        <input type="hidden" name="relatedarticles_hidden" value="Y">
        <label>*Multiple selection for all the taxonomies.</label>
        <!--<input type="text" name="relatedarticles_taxonomies" value="<?php echo $relatedarticles_taxonomies; ?>">-->
        <br>
        <select multiple="multiple" name="relatedarticles_taxonomies[]" id="relatedarticles_taxonomies">
            <?php
                foreach ($taxonomies as $key => $value) {
                    
                    $selection = "";
                    if( in_array($key, $relatedarticles_taxonomies) ){
                        $selection = ' selected="selected" ';
                    }else{
                        $selection = ' ';
                    }
                    echo '<option value="' . $key . '"' . $selection . '>' . $value . '</option>';
                }
            ?>
        </select>
        <br>
        <input type="submit" name="Submit" value="<?php _e('Update Options') ?>" />
    </form>
</div>