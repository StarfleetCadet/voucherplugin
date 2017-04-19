<style type="text/css">

</style>

<div class="template-container">
    <h3><?php echo $name; ?></h3>

    <img class="template-image-preview" src="<?php echo $img_url; ?>">

    <form action="<?php echo get_admin_url()."admin-post.php" ?>" method="post">
        <input type="hidden" name="template_id" value="<?php echo $templateId;?>">
        <input type='hidden' name='action' value='delete-template' />
        <input type="submit" value="Löschen"/>
    </form>

    <form action="<?php echo get_admin_url()."admin-post.php" ?>" method="post">
        <input type="hidden" name="template_id" value="<?php echo $templateId;?>">
        <input type='hidden' name='action' value='preview-template' />
        <input type="submit" value="Preview"/>
    </form>
</div>