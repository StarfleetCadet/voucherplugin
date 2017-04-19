<?php
/**
 * @var AdminMainPage $this
 */
?>
<style type="text/css">
    .new-template-container {

    }
    .new-template-container input {
        padding-bottom: 10px;
        width: 100%;
        clear: both;
    }
    .new-template-container label {
        padding-bottom: 10px;
        width: 150px;
        clear: both;
    }

</style>

<div class="new-template-container template-container">
    <h4>Neues Template erstellen</h4>
    <form action="<?php echo get_admin_url()."admin-post.php" ?>" method="post" enctype="multipart/form-data">

        <input type="text" name="name" placeholder="Name"><br />

        <label for="image">Bild:</label>
        <input type="file" name="image" /><br />

        <label for="product">Produkt:</label>
        <?php $this->draw_product_selection() ?><br />

        <input type='hidden' name='action' value='new-template' />
        <input type="submit" value="Erstellen"/>
    </form>
</div>