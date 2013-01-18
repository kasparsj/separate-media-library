<div class="wrap">
    <div class="icon32" id="icon-options-general"><br></div>
    <h2>Separate Media Library</h2>
    <!--Some optional text here explaining the overall purpose of the options and what they relate to etc.-->
    <form method="post" action="options.php">
        <?php settings_fields('separate_media_library'); ?>
        <!--<h3>Main Section</h3>-->
        <p>Check post types which media libraries you would like to separate:</p>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="sml_post_types">Post types:</label></th>
                    <td>
                        <input type="checkbox" id="sml_post_type_post" name="separate_media_library[post_types][]" value="post"<?=in_array('post', $options["post_types"]) ? ' checked="checked"' : ''?> />
                        <label for="sml_post_type_post">Post</label>&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" id="sml_post_type_page" name="separate_media_library[post_types][]" value="page"<?=in_array('page', $options["post_types"]) ? ' checked="checked"' : ''?> />
                        <label for="sml_post_type_page">Page</label>&nbsp;&nbsp;&nbsp;
                    <?php foreach (get_post_types(array('_builtin' => false), 'objects') as $post_type): ?>
                        <input type="checkbox" id="sml_post_type_<?=$post_type->name?>" name="separate_media_library[post_types][]" value="<?=$post_type->name?>"<?=in_array($post_type->name, $options["post_types"]) ? ' checked="checked"' : ''?> />
                        <label for="sml_post_type_<?=$post_type->name?>"><?=$post_type->label?></label>&nbsp;&nbsp;&nbsp;
                    <?php endforeach; ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php submit_button(); ?>
    </form>
</div>
