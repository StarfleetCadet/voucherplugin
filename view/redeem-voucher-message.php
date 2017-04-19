<?php
/**
 * @var $voucher Voucher
 * @var $status string
 */
?>
<div class="redeem-info">
    <?php if ($status == "VALID") echo "<h1 class='redeem-success'>GÜLTIG</h1>"; ?>
    <?php if ($status == "NOT_FOUND") echo "<h1 class='redeem-failed'>GUTSCHEIN NICHT GEFUNDEN</h1>"; ?>
    <?php if ($status == "INVALID") echo "<h1 class='redeem-failed'>UNGÜLTIG</h1>"; ?>
    <?php if ($status == "ERROR") echo "<h1 class='redeem-failed'>FEHLER BEIM EINLÖSEN DES GUTSCHEINS</h1>"; ?>
    <table>
        <tr>
            <td>Code:</td>
            <td><?php if ($voucher != null) echo $voucher->getCode()?></td>
        </tr>
        <tr>
            <td>Ausgestellt am:</td>
            <td><?php if ($voucher != null) echo $voucher->getIssued()?></td>
        </tr>
        <tr>
            <td>Wert:</td>
            <td><b><?php if ($voucher != null) echo $voucher->getValue()?>€</b></td>
        </tr>
        <tr>
            <td>Eingelöst am:</td>
            <td><?php if ($voucher != null && $voucher->getRedeemed() != null) echo $voucher->getRedeemed()?></td>
        </tr>
    </table>
</div>