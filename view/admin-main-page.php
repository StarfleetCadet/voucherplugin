<?php
/**
 * @var AdminMainPage $this
 */
?>

<style type="text/css">
    .template-wrapper {

    }

    .template-container {
        border: solid;
        border-width: 1px;
        padding: 5px;
        width: 300px;
        float: left;
        margin: 10px;
    }

    .template-image-preview {
        width: 280px;
        margin-left: 10px;
    }

    .redeem-info {
        border: solid;
        border-width: 1px;
        padding:5px;
        width: 300px;
    }

    .redeem-success {
        color: green;
        size: 30rem;
        font-weight: bold;
    }

    .redeem-failed {
        color: red;
        size: 70rem;
        font-weight: bold;
    }
</style>

<div class="wrap">
    <h2>Voucher Plugin Administration</h2>
    <?php
    echo "<form action='".get_admin_url()."admin-post.php' method='post'>";
    echo "Template-ID<input type='text' name='template_id'>";
    echo "<input type='hidden' name='action' value='voucherplugin-testbutton' />";
    echo "<input type='submit' value='Gutschein erstellen'/>";


    echo "</form>";
    ?>

    <?php
    if ($this->message_to_show != null) echo "<h1>".$this->message_to_show."</h1>";
    ?>

    <h3>Gutschein einlösen</h3>

    <form action="/wp-admin/admin.php" method='get'>
        <label>Gutscheincode:</label>
        <input type="text" name="code">
        <input type='hidden' name='page' value='<?php echo Config::PLUGIN_NAME; ?>-admin-page.php' />
        <input type="submit" value="Einlösen">
    </form>

    <?php $this->show_redeem_voucher_message(); ?>

    <h3>Gutschein anzeigen</h3>

    <?php echo "<form action='".get_admin_url()."admin-post.php' method='post'>"; ?>
        <label>Gutscheincode:</label>
        <input type="text" name="code">
        <input type='hidden' name='action' value='show-voucher' />
        <input type="submit" value="Anzeigen">
    </form>

    <h3>Gutschein Templates</h3>
    <div class="template-wrapper">

    <?php $this->draw_new_template_form()?>

    <?php
    foreach($templates as $template) {
        $this->draw_template($template);
    } ?>
    </div>
</div>